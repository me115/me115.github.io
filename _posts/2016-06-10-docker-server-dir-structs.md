---
layout: post
title: Docker镜像文件存储结构
categories: [docker]
description: Docker镜像文件存储结构
keywords: docker
---


docker相关文件存放在：/var/lib/docker目录下

镜像的存储结构主要分两部分，一是镜像ID之间的关联，一是镜像ID与镜像名称之间的关联，前者的结构体叫Graph，后者叫TagStore.

- /var/lib/docker/repositories-aufs TagStore的存储地方，里面有image id与reponame ，tag之间的映射关系. aufs是driver名
- /var/lib/graph/<image id> 下面没有layer目录，只有每个镜像的json描述文件和layersize大小
- /var/lib/docker/aufs/diff/<image id or container id> 每层layer与其父layer之间的文件差异，有的为空，有的有一些文件(镜像实际存储的地方)
- /var/lib/docker/aufs/layers/<image id or container id> 每层layer一个文件，记录其父layer一直到根layer之间的ID，每个ID一行。大部分文件的最后一行都一样，表示继承自同一个layer.
- /var/lib/docker/aufs/mnt/<image id or container id> 有容器运行时里面有数据(容器数据实际存储的地方,包含整个文件系统数据)，退出时里面为空

- /var/lib/docker/volumes volumes卷的存储地

## repositories-aufs
/var/lib/docker/repositories-aufs 存放的是本地所有仓库TagStore；可以对应到docker images 显示的信息；

```shell
root@n6-026-137:registry# cat /var/lib/docker/repositories-aufs |python -mjson.tool  
{
    "Repositories": {
        "baseimage": {
            "install_test_service": "7fc2548d2b3c6fa82d7a4da8a67e8619145d21b48009d53eeef2c00823d0f118",
            "v0.1": "5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83"
        },
        "baseimage.pre": {
            "v1.0": "3e2059d8241e7a623972728cf6ec7093eb54a77f481c9cd0b0a86589ea1368d9"
        },
......
```

## Graph信息
/var/lib/docker/graph/ 下存储的是有镜像；每个镜像一个uuid值命名的目录，可以从repositories-aufs中找到具体是哪个镜像；

比如：baseimage:v1.0的5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83 ：

```shell
root@n6-026-137:registry# ls /var/lib/docker/graph/5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83/
checksum  json  layersize

```
json -保存着关于这个镜像的元数据
layersize – 一个整数，表示layer的大小。

镜像元数据：

```shell
root@n6-026-137:registry# cat /var/lib/docker/graph/5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83/json|python -mjson.tool
{
    "Size": 0,
    "architecture": "amd64",
    "author": "Sebastian Krohn <seb@gaia.sunn.de>",
    "config": {
        "AttachStderr": false,
        "AttachStdin": false,
        "AttachStdout": false,
        "Cmd": [
            "/lib/systemd/systemd"
        ],
        "Domainname": "",
        "Entrypoint": null,
        "Env": [
            "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
            "DEBIAN_FRONTEND=noninteractive",
            "container=docker"
        ],
        "ExposedPorts": null,
        "Hostname": "bcad5a346f31",
        "Image": "8c7a4f4e1f099c9a59fcfa5bd4859f0b58e6b49c36e9a19ab0f453b4244c2cd2",
        "Labels": {},
        "MacAddress": "",
        "NetworkDisabled": false,
        "OnBuild": [],
        "OpenStdin": false,
        "PortSpecs": null,
        "StdinOnce": false,
        "Tty": false,
        "User": "",
        "VolumeDriver": "",
        "Volumes": {
            "/run": {},
            "/run/lock": {},
            "/sys/fs/cgroup": {},
            "/tmp": {}
        },
        "WorkingDir": ""
    },
    "container": "9f909663a7c412464a9645800887e3104e04c8e070fa7b4741758ded5de6903f",
    "container_config": {
        "AttachStderr": false,
        "AttachStdin": false,
        "AttachStdout": false,
        "Cmd": [
            "/bin/sh",
            "-c",
            "#(nop) CMD [\"/lib/systemd/systemd\"]"
        ],
        "Domainname": "",
        "Entrypoint": null,
        "Env": [
            "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
            "DEBIAN_FRONTEND=noninteractive",
            "container=docker"
        ],
        "ExposedPorts": null,
        "Hostname": "bcad5a346f31",
        "Image": "8c7a4f4e1f099c9a59fcfa5bd4859f0b58e6b49c36e9a19ab0f453b4244c2cd2",
        "Labels": {},
        "MacAddress": "",
        "NetworkDisabled": false,
        "OnBuild": [],
        "OpenStdin": false,
        "PortSpecs": null,
        "StdinOnce": false,
        "Tty": false,
        "User": "",
        "VolumeDriver": "",
        "Volumes": {
            "/run": {},
            "/run/lock": {},
            "/sys/fs/cgroup": {},
            "/tmp": {}
        },
        "WorkingDir": ""
    },
    "created": "2016-04-21T10:42:07.532547156Z",
    "docker_version": "1.7.0",
    "id": "5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83",
    "os": "linux",
    "parent": "8c7a4f4e1f099c9a59fcfa5bd4859f0b58e6b49c36e9a19ab0f453b4244c2cd2"
}

```

查看baseimage:v1.0的有多少层 ：
```shell
root@n6-026-137:docker# vi /var/lib/docker/aufs/layers/5a337e287e253c6cf573c0a64449e5bd648fdebd3787e0cc36f3b66c1c89ce83
  1 8c7a4f4e1f099c9a59fcfa5bd4859f0b58e6b49c36e9a19ab0f453b4244c2cd2
  2 83233435161ad3b1155260e1fc2592e0b0bf20840e8862216cbfa5a9cd6f538c
  3 4a81de8c1c50f0c08a3b3810d34448a247ee5dc5a5407f79424a90f6c94c84d3
  4 a7643b953718f9bee57ac8a4e85a4386e9268a89637fcb78c76a6ad9ceaa7649
  5 46f55699874da611db2114a30d1b39b9e59bc97d383e316502e195115ec1a7dd
  6 caa0a879a980170ff647ef7465f987bb035b896a2085e26d7e39887860bdcd98
  7 fc08f28a2a36f3607aa2999a0c5702775f289b0db0be84dc8ec1dc4d8f0ec740
  8 de1c21c78ec7ee7dde44e34071515c0ece8d2b7688e7a863216ba6a7845e6b8b
  9 fb35fe2ca7e9b08ca663811a2509a3678100ebc897aa89560172d2f485d224ff
 10 472023c9482e1183a61967c7ee2fdbc7970c80b8bfe3b3825f00a257851da081
 11 b6c85061d955fae31c567009c1f82bf9b1e6d57634395aae68e9557914ce29d5
 12 38c2a8f4286db6e4bd9df16819ac0e8bab51b704dad5f3381546edc509a13cbc
 13 23f5441787467e3cb64f1a865094b77479803584b9ee929371528086525378ed
 14 a7baf4d8d152cd332e873448471c6e0f8994774b19b20368e010f30e8f04ef42
 15 d1cc0e6af8490e94cde088bec8b267615464eeb1af20bcbe577d055ff231e634
 16 6b8a1ecb2364a8ea74873a6bac5104699c260a392ce403ca137b2ce5c5235114
```

