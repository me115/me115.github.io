---
layout: post
title: docker registry 后端存储接入ceph object gateway（RGW）
categories: [docker,ceph]
description: 
keywords: ceph,docker
---

# docker registry 后端存储接入ceph object gateway（RGW）
docker registry 后端存储支持多种存储，默认是本地的文件系统；registry 作为 docker image的存储，对容量的要求很大，一般生产使用都需要接入分布式文件系统；

docker registry 后端存储支持多种存储，默认是本地的文件系统；registry 作为 docker image的存储，对容量的要求很大，一般生产使用都需要接入分布式文件系统；
因为视频组使用ceph比较成熟，于是直接使用视频组的ceph来做后端存储；

关于ceph的介绍，参考之前的文章介绍；
docker registry 接入ceph 有两种接口，一种是rgw的s3接口，另一种是rwg的swift借口；这两种接口都是restful 接口，都是通过ceph object gateway和 ceph交互；而直接通过rados原生接口和ceph交互，目前docker registry不支持；

我们选择的是swift 接口；

## 关于ceph object gateway（RGW）
和直接访问ceph的rados接口不同，使用ceph对象网关，需要额外安装网关组件；0.8版 ceph之前，要提供 Ceph 对象存储服务，必须在对应主机（即 gateway host ）上安装 Apache 和 Ceph 对象网关守护进程。
0.8版 之后不用再安装Apache 和 FastCGI， Ceph Object Gateway 运行在 Civetweb (embedded into the ceph-radosgw daemon); 
Civetweb 是一个轻量级的web服务器，基于 Mongoose 开发；


### 安装CEPH OBJECT GATEWAY
预检查：
```shell
wget -q -O- 'https://download.ceph.com/keys/release.asc' | sudo apt-key add -

echo deb http://download.ceph.com/debian-hammer/ $(lsb_release -sc) main | sudo tee /etc/apt/sources.list.d/ceph.list
## 现在一般都是jessie版本了：

echo deb http://download.ceph.com/debian-hammer/ jessie main | sudo tee /etc/apt/sources.list.d/ceph.list

 
sudo apt-get update && sudo apt-get install ceph-deploy
```

安装gateway：
```shell
$hostname -s
n6-026-137
$ceph-deploy install --rgw n6-026-137
ceph-deploy install --rgw  in24-158
```

设置目录：
```shell
ceph-deploy --overwrite-conf admin in24-158
```

给这个网关创建一个密钥环
```shell
ceph-authtool --create-keyring /etc/ceph/ceph.client.radosgw.keyring
creating /etc/ceph/ceph.client.radosgw.keyring

root@n6-026-137:/etc/ceph# cat ceph.client.radosgw.keyring 
[client.radosgw.gateway]
	key = AQBQU49Xn+15BxAAeFUXtQWQ9M3pXZy38Tg7yw==
```

添加key的权限：
```shell
sudo ceph-authtool -n client.radosgw.gateway --cap osd 'allow rwx' --cap mon 'allow rwx' /etc/ceph/ceph.client.radosgw.keyring
```
add the key to your Ceph Storage Cluster
```shell
ceph -k /etc/ceph/ceph.client.admin.keyring auth add client.radosgw.gateway -i /etc/ceph/ceph.client.radosgw.keyring
```

更新/etc/ceph/ceph.conf配置，加入网关配置信息：
```shell
[client.radosgw.gateway]
host = in24-158
keyring = /etc/ceph/ceph.client.radosgw.keyring
rgw_frontends = "civetweb port=9880"
rgw socket path = ""
log file = /var/log/radosgw/client.radosgw.gateway.log
```

将以下三个文件分发到 ceph cluster 各个节点和网关对象的部署节点：
/etc/ceph/ceph.conf
/etc/ceph/ceph.client.admin.keyring
/etc/ceph/ceph.client.radosgw.keyring

在网关节点上，创建数据存放目录：
```shell
mkdir -p /var/lib/ceph/radosgw/ceph-radosgw.gateway
```

启动网关：
```shell
sudo /etc/init.d/radosgw start
```

web访问,ok!
http://10.4.24.158:9880/

```shell
<ListAllMyBucketsResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
<Owner>
<ID>anonymous</ID>
<DisplayName/>
</Owner>
<Buckets/>
</ListAllMyBucketsResult>
```

## 创建用户
为了使用 REST 接口，首先需要创建一个初始 Ceph 对象网关用户。然后，为 Swift 接口创建一个子用户，再为这个用户创建 secret key。

创建用户
```shell
radosgw-admin user create --uid="dockerlucky" --display-name="Docker User"
```

创建子用户
```shell
radosgw-admin subuser create --uid=dockerlucky --subuser=dockerlucky:swift --access=full
{
    "user_id": "dockerlucky",
    "display_name": "Docker User",
    "email": "",
    "suspended": 0,
    "max_buckets": 1000,
    "auid": 0,
    "subusers": [
        {
            "id": "dockerlucky:swift",
            "permissions": "full-control"
        }
    ],
    "keys": [
        {
            "user": "dockerlucky:swift",
            "access_key": "CMDHH9H8G80GGHHNVT9U",
            "secret_key": ""
        },
        {
            "user": "dockerlucky",
            "access_key": "JI3FJ10KG54QP1JO8E07",
            "secret_key": "9TK5Z6zj9XKoEeMWldLA01HapdtaMo2NArMgRM6s"
        }
    ],

```

创建key:
```shell
radosgw-admin key create --subuser=dockerlucky:swift --key-type=swift --gen-secret
{
    "user_id": "dockerlucky",
    "display_name": "Docker User",
    "email": "",
    "suspended": 0,
    "max_buckets": 1000,
    "auid": 0,
    "subusers": [
        {
            "id": "dockerlucky:swift",
            "permissions": "full-control"
        }
    ],
    "keys": [
        {
            "user": "dockerlucky:swift",
            "access_key": "CMDHH9H8G80GGHHNVT9U",
            "secret_key": ""
        },
        {
            "user": "dockerlucky",
            "access_key": "JI3FJ10KG54QP1JO8E07",
            "secret_key": "9TK5Z6zj9XKoEeMWldLA01HapdtaMo2NArMgRM6s"
        }
    ],
    "swift_keys": [
        {
            "user": "dockerlucky:swift",
            "secret_key": "5bkCwc2PFKPLmJoYnvgAOksBlAY03TCk5HFLK6oM"
        }
    ],
    "caps": [],
    "op_mask": "read, write, delete",
    "default_placement": "",
    "placement_tags": [],
    "bucket_quota": {
        "enabled": false,
        "max_size_kb": -1,
        "max_objects": -1
    },
    "user_quota": {
        "enabled": false,
        "max_size_kb": -1,
        "max_objects": -1
    },
    "temp_url_keys": []
}

```

## 使用swiftclient验证swift接口

测试swift的访问需要安装 swiftclient：
```shell
sudo pip install --upgrade setuptools
sudo pip install --upgrade python-swiftclient
```

切换到swiftclient目录
/opt/colin/MYSITEENV/lib/python2.7/site-packages/swiftclient，

执行swiftclient shell：
```shell
swiftclient$ python shell.py -A http://10.4.24.158:9980/auth/1.0 -U dockeruser:swift -K 'oY894WlkjlyUAxHacYNMAyR8dpR3ZzlRoBJbt3xW' stat
                    Account: v1
                 Containers: 5
                    Objects: 0
                      Bytes: 0
                X-Timestamp: 1469590199.89180
X-Account-Bytes-Used-Actual: 0
                 X-Trans-Id: tx0000000000000000012e8-0057982ab7-b215dd-default
               Content-Type: text/plain; charset=utf-8
              Accept-Ranges: bytes

# create a bucket(container):
swiftclient$ python shell.py -A http://10.4.24.158:9980/auth/1.0 -U dockeruser:swift -K 'oY894WlkjlyUAxHacYNMAyR8dpR3ZzlRoBJbt3xW' post test1

# list container( everything works well)
swiftclient$ python shell.py -A http://10.4.24.158:9980/auth/1.0 -U dockeruser:swift -K 'oY894WlkjlyUAxHacYNMAyR8dpR3ZzlRoBJbt3xW' list
colin
my-new-bucket
registry
registry22
test1

```

## 使用py脚本验证swift接口
和swift交互，首先需要授权，将用户名和密码组合请求rgw的接口，获取一个token；后续的交互都需要带上这个token
1. 获得授权
```shell
$ ./auth_get.py 
AUTH_rgwtk0f000000646f636b65723135383a7377696674ab66fa7a0ea6d5874c39a4574d2506194cb396c0af6a5efdc5b502e927b004f3830f64c2
http://10.4.24.158:9980/swift/v1
204
```

1. 将这个AUTH_rgw token带上，后续通信，可以创建/列出容器、put/get object；
   脚本源码： https://github.com/me115/ceph_oject_gateway_client/tree/master/gw_swift_test

## 配置docker registry

```shell
# vi config.ceph.136.yml 
version: 0.1 
log:
  fields:
    service: registry
storage:
  cache:
    blobdescriptor: inmemory
  swift:
    authurl: http://10.6.26.136:9980/auth/v1
    username: dockerlucky:swift
    password: 5bkCwc2PFKPLmJoYnvgAOksBlAY03TCk5HFLK6oM
    container: registry136
http:
  addr: :5000
  headers:
    X-Content-Type-Options: [nosniff]
health:
  storagedriver:
    enabled: true
    interval: 10s 
    threshold: 3
```

启动 registry：
```shell
docker run -d --net=host -v `pwd`/config.ceph.136.yml:/etc/docker/registry/config.yml -v /opt/docker/registry/data:/var/lib/registry --name docker-registry-ceph registry:2.5.0
```


试着向registry push一个镜像；
```shell
docker pull busybox
docker tag busybox:latest n6-026-137.byted.org:443/busybox:v1.0
docker push n6-026-137.byted.org:443/busybox:v1.0
```
能push成功就算搭建ok；

## 遇到的问题
注：我使用的docker registry 版本为2.5.0，ceph的版本为9.0.3，ceph oject gateway的为最新版10.2.2（因为ceph集群是很早就搭建了，并在线上稳定运行）；最新版本的ceph oject gateway 和 ceph 交互有问题， gw的新 put 操作 ceph 会报错不支持；
然后我将ceph gateway的版本更改为 和 ceph 一样的版本9.0.3；结果发现rgw的9.0.3的 Head请求有bug； docker registry PUT操作存放数据之后会跟一个HEAD请求验证是否存入；这个head请求返回没有http status；
跟踪代码应该是civetweb 的问题，9.0.*属于ceph的开发版，一般也没有经过生产的验证；将ceph gw版本推进一个版本，使用9.1.0，并使用最新版的civetweb ；问题解决；
关于这个问题的细节详情，详见下一篇文章；《排查 ceph object gateway 早期版本 bug》



## 参考
radosgw
http://docs.ceph.com/docs/master/radosgw/

docker registry的配置：
https://docs.docker.com/registry/configuration/
swift 接口参数说明：
https://docs.docker.com/registry/storage-drivers/swift/

Docker Private Registry(Ceph Swift) 搭建笔记
http://ivanjobs.github.io/2016/05/07/docker-registry-study/

ceph docker registry：
https://github.com/ceph/ceph-docker/tree/master/docker-registry







