---
layout: post
title: 修复docker pull image failed
categories: docker
description: 
keywords: docker
---

# 修复docker pull image failed

## docker pull报错

```
message":"Get https://n6-026-137.byted.org/v1/_ping: Not Found"},"error":"Get https://n6-026-137.byted.org/v1/_ping: Not Found
```

## 排查

打开docker的调试开关，给dockerd的启动参数中加入-D参数：

```
vi /lib/systemd/system/docker.service
ExecStart=/usr/bin/dockerd -D -H unix:///var/run/docker.sock -g="/opt/docker" 
```

dockerd 的日志（docker pull/push的日志）会记录到/var/log/daemon.log中，再次触发错误之后，在daemon.log中没有找到更多有用的信息：

> Dec  1 17:12:13 n6-026-137 dockerd[138149]: time="2016-12-01T17:12:13.537485265+08:00" level=error msg="Attempting next endpoint for push after error: Get https://sandbox.hub.byted.org/v1/_ping: Not Found"

因为这个是测试服务器，dockerd上通过的信息并不到，直接strace docker的进程，找到出错的原因：

ERR_DNS_FAIL

```
strace -ff -s256 -p 138149 2>&1 |awk '{print $0}' > a.out
[pid 191579] <... read resumed> "HTTP/1.0 404 Not Found\r\nServer: squid/3.1.20\r\nMime-Version: 1.0\r\nDate: Thu, 01 Dec
    2016 09:14:36 GMT\r\nContent-Type: text/html\r\nContent-Length: 3281\r\nX-Squid-Error: ERR_DNS_FAIL 0\r\nVary: Accept-Lang
    uage\r\nContent-Language: en\r\n\r\n<!DOCTYPE html PUBLIC \"-//W3C"..., 4096) = 3508

```

## 原因

因为golang有自己的 pure go resolver 实现，该版本实现有以下特点：

1. 默认情况下会直接查询 DNS 服务器， 不会使用本机 nscd DNS缓存；
2. 它会拒绝DNS回复内容超过512bytes的回报；

通过设置环境变量 GODEBUG=netdns=cgo 启动，在运行时强制使用系统 resolver，可以提高DNS响应速度，减少DNS服务器压力，不会拒绝大于512bytes的回报；

## 修改

添加一下环境变量后，重启go服务（dockerd服务）：

GODEBUG=netdns=cgo

## ref

https://github.com/docker/docker/issues/18842

