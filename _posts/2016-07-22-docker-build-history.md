---
layout: post
title: 如何查看一个image的构建历史
categories: [docker]
description: some word here
keywords: docker, docker build
---


# 如何查看一个image的构建历史

构建镜像是由一一层组成的，而每一层都对应dockefile中的一行命令；
当手上只有一个上层的应用imags，希望了解每一层都做了哪些工作时，可查看image的构建历史；

示例：查看toutiao.debian:v1.1的构建历史:

- 查询image的digest标识

```shell
$cat /var/lib/docker/image/aufs/repositories.json  |python -mjson.tool
...
        "toutiao.debian": {
            "toutiao.debian:v1.0": "sha256:5b8186e36a2949e441c13e61ca3595edefc2200632843ba5add6ff49ae1300c4",
            "toutiao.debian:v1.0.1": "sha256:17f5b2db39642ab925051fd3e65ef790ba1c561214bc2a4ce35b7f4b0c448d1b",
            "toutiao.debian:v1.1": "sha256:2709185eaaf73cdee7f64d616465fc8acc59d874782d9e217802b967612ada13"
        },
...
```

- 通过digest查看构建历史


```shell
$ cat /var/lib/docker/image/aufs/imagedb/content/sha256/2709185eaaf73cdee7f64d616465fc8acc59d874782d9e217802b967612ada13

"history": [
        {
            "created": "2016-06-09T21:28:42.397841831Z",
            "created_by": "/bin/sh -c #(nop) ADD file:76679eeb94129df23c99013487d6b6bd779d2107bf07d194a524fdbb6a961530 in /"
        },
        {
            "created": "2016-06-09T21:28:43.776404816Z",
            "created_by": "/bin/sh -c #(nop) CMD [\"/bin/bash\"]",
            "empty_layer": true
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T10:28:01.591127885Z",
            "created_by": "/bin/sh -c #(nop)  MAINTAINER Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "empty_layer": true
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T11:19:16.773578057Z",
            "created_by": "/bin/sh -c #(nop) %s %s in %s  ADD dir:20d430614bbe440767a7a48dd43dc74bf9e5701f99408866a09c47b33f9fe19a /bd_build"
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T11:19:18.388554066Z",
            "created_by": "/bin/sh -c chmod +x /bd_build/*.sh"
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T11:22:58.748299899Z",
            "created_by": "/bin/sh -c /bd_build/prepare.sh &&  \t/bd_build/system_services.sh &&  \t/bd_build/cleanup.sh"
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T11:22:59.988103785Z",
            "created_by": "/bin/sh -c #(nop)  VOLUME [/sys/fs/cgroup /run /run/lock /tmp]",
            "empty_layer": true
        },
        {
            "author": "Phusion <info@phusion.nl> WebArchTeam <webarch@bytedance.com>",
            "created": "2016-07-08T11:23:00.836078046Z",
            "created_by": "/bin/sh -c #(nop)  CMD [\"/lib/systemd/systemd\"]",
            "empty_layer": true
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-08T13:16:01.897006391Z",
            "created_by": "/bin/sh -c #(nop) MAINTAINER WebArchTeam \"webarch@bytedance.com\"",
            "empty_layer": true
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-08T13:16:02.105252029Z",
            "created_by": "/bin/sh -c #(nop) ENV IS_DOCKER_ENV=true",
            "empty_layer": true
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-08T13:16:02.27115877Z",
            "created_by": "/bin/sh -c #(nop) ENV PATH=/home/tiger/system_op/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
            "empty_layer": true
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-10T02:35:05.470641312Z",
            "created_by": "/bin/sh -c #(nop) ADD dir:a5317096f078495ec66fa16a80264f1a4df8d1c6c8aa93429c9f6169deea60bc in /bd_build/"
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-10T02:35:06.666288927Z",
            "created_by": "/bin/sh -c chmod +x /bd_build/*.sh"
        },
        {
            "author": "WebArchTeam \"webarch@bytedance.com\"",
            "created": "2016-07-10T02:35:09.700608813Z",
            "created_by": "/bin/sh -c /bd_build/deploy_debian.sh &&     /bd_build/clean_up.sh"
        }
```
