---
layout: post
title: 删除docker registry里的images
categories: [docker]
description: some word here
keywords: docker, docker registry
---


# 删除docker registry里的images

删除本地的images，我们有 docker rmi, 但删除private registry中的image，docker没有提供好用的命令行工具；而当我们需要更新registry中的镜像时，如果不删除，registry会认为我们的镜像已在库中，而忽略docker push操作（registry 2.4行为）；

## 从registry宿主机上操作
一个repo的由两部分组成，索引和分层数据；索引存放在 docker/registry/v2/repositories 中，数据存放在 docker/registry/v2/blobs 目录中；
删除的脚本干的活很简单， 从索引中找到组成这个image的分层数据hashid（digest标识），然后到blobs目录下找到删除即可；

[这个脚本](https://github.com/burnettk/delete-docker-registry-image)就实现了这个功能；


### 安装
```shell
curl https://raw.githubusercontent.com/burnettk/delete-docker-registry-image/master/delete_docker_registry_image.py | sudo tee /usr/local/bin/delete_docker_registry_image >/dev/null
sudo chmod a+x /usr/local/bin/delete_docker_registry_image

# 设置registry的环境变量：
export REGISTRY_DATA_DIR=/opt/docker/registry/data/docker/registry/v2
```

### 删除image

- 很棒，我们可以试运行一下，这个会列出来执行这个脚本将要删除的文件，不会执行真实的删除操作:
```shell
delete_docker_registry_image --image testrepo/awesomeimage --dry-run
```

- 停掉 registry 在不停registry的情况下执行删除，如果registry正在读取这个文件，可能会引起异常

- 删除
```shell
delete_docker_registry_image --image testrepo/awesomeimage 
```

注：这里有个风险，组成这个image的底层分片数据也可能是其他images的一部分，如此这样粗暴的删除该image的所有分层数据会导致相同引用的image无法使用；更为安全的做法是针对每个分层数据，再到索引区中反向查询，确认没有引用的再删除；（以上这个脚本没有实现这步）
所以，这个脚本更多的适用于清理完全不用的镜像旧数据；

另外，以上针对的是V2版本，针对docker registry v1版本删除脚本在这里：
https://gist.github.com/kwk/c5443f2a1abcf0eb1eaa


## 更安全的方式

官方提供了rest api来执行删除操作：

```shell
curl -u <username>:<password> -X DELETE https://<DTR HOST>/api/v0/repositories/<namespace>/<reponame>/manifests/<reference>


```

例如，要删除toutiao.baseimage:v1.0：

```shell
curl -u myname:mypassword -X DELETE https://n6-026-137.byted.org:443/api/v0/repositories/toutiao.baseimage/manifests/v1.0
```
也可使用digset：
```shell
curl -X DELETE /v2/<name>/manifests/<reference>

curl -u myname:mypassword -X DELETE curl -X DELETE https://n6-026-137.byted.org:443/v2/toutiao.baseimage/manifests/sha256:fcea2b4ed4ffa3dd3e86c795adcca3e525e1cc7a55ad99884d15008547ae73a7
```

这个api执行的结果是在registry中删除了该repo的索引；数据区没有任何影响；registry可以启动一个垃圾回收的任务（也可设置为定期执行），垃圾回收所做的工作是删除没有任何image引用的数据；

## ref
private registry delete images
https://docs.docker.com/docker-trusted-registry/repos-and-images/delete-images/


