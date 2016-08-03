---
layout: post
title: 优化docker push到private registry的性能
categories: [docker]
description: docker registry
keywords: docker registry, docker
---

# 优化docker push到private registry的性能

优化前，构建服务每次构建一个新的images后推送到docker registry，耗时大约1分40秒左右；（image大小780M左右）
优化之后，耗时20s左右；

## 原因分析

docker registry 在升级到 v2 后加入了很多安全相关检查，使得原来很多在 v1 already exist 的层依然要 push 到 registry,并且由于 v2 中的存储格式变成了 gzip，在镜像压缩过程中占用的时间很有可能比网络传输还要多。

docker push 的大致流程：

1. 将该层文件系统压缩成本地的一个临时文件
2. 上传文件至 registry
3. 本地删除临时文件，库的元数据 传给 registry
4. registry 计算上传压缩包 digest 并进行校验
5. registry 将压缩包传输至后端存储文件系统
6. 重复 1-5 直至所有层传输完毕
7. 计算镜像的 manifest 并上传至 registry 重复 3-5

此外判断 already exist skip pushing 的条件变严格了，必须是本地计算过digest 且该 digest 对应 repo 存在在本地才可以。

docker images由多层组成，每一层有一个唯一标识（digest），docker push时，服务端会校验，如果对应的层在服务端已经存在就不会再传输；
通过日志发现，即使有重复的层（bashimage层），docker push还是传输了一部分；


## 优化措施
### 减少应用的层数（优化dockefile）
减少总的层数，避免hash值的计算开销；（v1版本的registry hash值是随机生成的一个，v2版本的hash值是根据内容来生成的，这个开销代价不小）

一个dockerfile指令就会占用一层；将同一类shell指令合并就显得很有必要；层数少，在压缩和网络交互的时候就快了：

dockerfile书写参考：
http://dockone.io/article/255
Dockerfile 语法检查及优化工具
http://dockerfile-linter.com/

toutiao.debian 之前24层, 改进后为 14层:

```shell
root@n6-026-137:toutiao.debian(master)# docker push n6-026-137.byted.org:443/toutiao.debian:v1.0
The push refers to a repository [n6-026-137.byted.org:443/toutiao.debian] (len: 1)
a3d5b484c554: Image already exists 
ec46209b260f: Image successfully pushed 
ee6450a4933e: Image successfully pushed 
f68cb164a8d3: Image successfully pushed 
0ad54c490b25: Image successfully pushed 
8a0448c0b765: Image successfully pushed 
8a0448c0b765: Buffering to Disk 
68e527118cd0: Image already exists 
b81f5e5b4ebb: Image successfully pushed 
c46b9f890cbe: Image successfully pushed 
da4b727f9b95: Image successfully pushed 
da4b727f9b95: Buffering to Disk 
d0d1e35542d3: Image already exists 
d1cc0e6af849: Image already exists 
Digest: sha256:0d1d71700badc71fc64f184848ce0d0abd2aca2174555f9161c23e9535878aeb
```


### 善用 .dockerignore
.dockerignore 可以减少构建时的文件传输，类似.gitignore, 一些与构建无关的代码也尽量放到 .dockerigonre 文件中。

### 避免传输重复的层
docker 和registry交互的过程中，对是否是重复的层判断不完善，导致即使registry已存在的部分层也进行的重复传输，升级docker registry：

升级之前的版本为：
docker 1.7  docker registry: 2.2

升级之后：
docker 1.11.2 docker registry:2.4

顺便提一下，docker 的最新预览版1.12有些问题，建议安装最新的稳定版:1.11.2:
使用docker 官方的install 指南，直接会安装最新版，安装稳定版的命令：

```shell
# 查看都有哪些版本：
apt-cache policy docker-engine
# 安装：
apt-get install -y -q docker-engine=1.11.2-0~jessie
```


### registry开启内存缓存

默认配置启动的registry没有使用开启缓存，registry的配置中可以增加缓存；
可选的有系统内存缓存（map），redis等kv系统；这里我们直接加上系统的内存：

```shell
storage:
  cache:
    blobdescriptor: inmemory
  filesystem:
    rootdirectory: /var/lib/registry
```


### 并行传输

Docker Registry目前分为V1和V2两个版本.
V1 registry 中镜像的每个layer 都包含一个json文件包含了父亲 layer 的信息.因此当我们 pull 镜像时需要串行下载，下载完一个 layer 后才知道下一个 layer 的 id 是多少再去下载.
V2 registry 在 image 的 manifest 中包含了所有 layer 的信息，客户端可以并行下载所有的 layer；

我们使用的是v2版本，使用客户端docker push的时候是并行传输，但在jenkins的插件中docker plugin中仍使用的串行方式进行传输，升级docker和registry之后，插件并行传输功能正常；

v1和v2的比较：
http://dockone.io/article/747


### nginx缓存区配置

docker registry 前端使用一层nginx，加大nginx缓冲区，详见：

[nginx缓冲区优化](https://wiki.bytedance.com/pages/viewpage.action?pageId=60297741)


## 判重的关键-digest标识

digest 是一个和镜像内容相关的字符串，相同的内容会生成相同的    digest。镜像layer判断是内容相关的.digest不是本地生成的,而是通过push操作,在Registry一方生成的.

当客户端推送镜像到Registry时会同时附带Image Manifest(也就是签名).Image Manifest也就是Docker镜像内容的一些JSON的定义.在Golang中的结构就如下所示,包含了镜像的名字,FSLayer的信息等等；

```shell
type ManifestData struct {
    Name          string             `json:"name"`
    Tag           string             `json:"tag"`
    Architecture  string             `json:"architecture"`
    FSLayers      []*FSLayer         `json:"fsLayers"`
    History       []*ManifestHistory `json:"history"`
    SchemaVersion int                `json:"schemaVersion"`
}
```

可以调用docker registry API来查看Manifest中的内容.

```shell
$ curl <domain>/v2/<name>/manifests/<tag>
```

Registry是通过下面的函数来根据Manifest的元数据来生成digest的(registry/handlers/images.go)

```shell
// PutImageManifest validates and stores and image in the registry.
func (imh *imageManifestHandler) PutImageManifest(w http.ResponseWriter, r *http.Request)
```

在客户端调用的时候通过消息头Docker-Content-Digest发送给对方

```shell
202 Accepted
Location: <url>
Content-Length: 0
Docker-Content-Digest: <digest>
```

例如，对于n6-026-137.byted.org:443/313:v160629.035009，查询manifest：

```shell
GET https://n6-026-137.byted.org/v2/313/manifests/v160629.035009
{
"name": "313",
"tag": "v160629.035009",
"architecture": "amd64",
"fsLayers": [
{
"blobSum": "sha256:a3ed95caeb02ffe68cdd9fd84406680ae93d633cb16422d00e8a7c22955b46d4"
},
{
"blobSum": "sha256:a3ed95caeb02ffe68cdd9fd84406680ae93d633cb16422d00e8a7c22955b46d4"
},
{
"blobSum": "sha256:8bfaf9538d2a3be631b43870fa8530ca7f27895047e48abc6fb833e6235da642"
},
{
"blobSum": "sha256:7f32d669882c238f4b06977b4f748e01d85cff62913018415fea8b7632b18483"
...
```

## ref
Docker 持续集成过程中的性能问题及解决方法
http://oilbeater.com/docker/2016/01/02/use-docker-performance-issue-and-solution.html
docker-registry的定制和性能分析
http://dockone.io/article/375
基于内容地址的Docker Registry2.0
http://sunxiang0918.cn/2015/09/20/%E5%9F%BA%E4%BA%8E%E5%86%85%E5%AE%B9%E5%9C%B0%E5%9D%80%E7%9A%84Docker-Registry2-0/

manifest 相关文档：
https://github.com/docker/distribution/blob/master/docs/spec/manifest-v2-1.md
https://github.com/docker/docker/issues/9015


