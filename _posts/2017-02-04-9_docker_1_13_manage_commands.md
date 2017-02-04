---
layout: post
title: Docker 1.13 管理命令
categories: [docker]
description: some word here
keywords: docker, docker cli
---

# Docker 1.13 管理命令
![](/images/posts/9-1.png)

## 1.12 CLI 的问题

Docker1.12 命令行接口（CLI）有40多个顶级命令，这些命令存在以下问题：

1. 没有归类组织，这让docker 新手很难学习；
2. 有些命令没有提供足够的上下文环境，以至于我们不知道是在操作image 还是container（eg：docker inspect）；这种在 image和 container 之间混合使用的命令让人困惑；
3. 命令的名字缺乏一致性；比如：docker images 是个复数，这个命令用来列出所有的images， 而docker ps 是个单数，这个命令用来列出所有的 container；

Docker 1.13 整理后，现在顶级命令有以下这些：
```shell
checkpoint  Manage checkpoints
container   Manage containers
image       Manage images
network     Manage networks
node        Manage Swarm nodes
plugin      Manage plugins
secret      Manage Docker secrets
service     Manage services
stack       Manage Docker stacks
swarm       Manage Swarm
system      Manage Docker
volume      Manage volumes
```
在1.13 中，列出所有 images 使用 docker image ls 取代之前的 docker images，类似的，docker container ls 列出所有的container （之前为docker ps），这样保持了不同类命令的一致性，新手更容易学习；

所有管理命令都有一些共同的子命令：
SUB-COMMAND	PURPOSE
ls:	List <category> (image, container, volume, secret, etc)
rm:	Remove <category>
inspect: 	Inspect <category>

默认所有的顶级命令都会显示，但是如果你设置DOCKER_HIDE_LEGACY_COMMANDS 为true后就只会显示管理类命令。

```shell
DOCKER_HIDE_LEGACY_COMMANDS=true docker --help
```

**1.13  之前的命令语法都依然可以使用，但建议都迁移到新的命令上来。**

比如，启动一个容器的语法：
```shell
docker container run -d -p 8091-8094:8091-8094 -p 11210:11210 arungupta/couchbase
```

## 原有命令和新管理命令之间的映射
| 1.12      | 1.13                | PURPOSE               |
| --------- | ------------------- | --------------------- |
| `attach`  | `container attach`  | 登录到一个运行的容器中           |
| `build`   | `image build`       | 从 Dockerfile 构建镜像     |
| `commit`  | `container commit`  | 根据 container’s 变更创建镜像 |
| `cp`      | `container cp`      | 在容器和本地文件系统之间复制文件/文件夹  |
| `create`  | `container create`  | 创建一个新的容器              |
| `diff`    | `container diff`    | 查看容器的变更详情             |
| `events`  | `system events`     | 获取服务端的实时事件            |
| `exec`    | `container exec`    | 在一个运行的容器中运行命令         |
| `export`  | `container export`  | 将容器的文件系统导出            |
| `history` | `image history`     | 查看镜像历史                |
| `images`  | `image ls`          | 列出所有镜像                |
| `import`  | `image import`      | 从本地文件系统导入镜像           |
| `info`    | `system info`       | 显示系统信息                |
| `inspect` | `container inspect` | 查看容器详情                |
| `kill`    | `container kill`    | 强杀运行中的容器              |
| `load`    | `image load`        | 从备份中加载镜像              |
| `login`   | `login`             | 登录到 Docker registry.  |
| `logout`  | `logout`            | 退出 Docker registry.   |
| `logs`    | `container logs`    | 查看容器日志                |
| `network` | `network`           | 管理容器网络                |
| `node`    | `node`              | 管理docker Swarm 节点     |
| `pause`   | `container pause`   | 暂时容器内进程               |
| `port`    | `container port`    | 列出容器的所有的端口映射          |
| `ps`      | `container ls`      | 列出所有容器                |
| `pull`    | `image pull`        | 从 仓库中拉取镜像             |
| `push`    | `image push`        | 推送镜像到仓库               |
| `rename`  | `container rename`  | 重命名容器                 |
| `restart` | `container restart` | 重启容器                  |
| `rm`      | `container rm`      | 删除容器                  |
| `rmi`     | `image rm`          | 删除镜像                  |
| `run`     | `container run`     | 在容器中运行命令              |
| `save`    | `image save`        | 将镜像保存为tar 备份文件        |
| `search`  | `search`            | 在仓库中搜素镜像              |
| `service` | `service`           | 管理 Docker 服务          |
| `start`   | `container start`   | 启动容器                  |
| `stats`   | `container stats`   | 实时查看容器统计信息            |
| `stop`    | `container stop`    | 停止容器                  |
| `swarm`   | `swarm`             | 管理 Docker Swarm       |
| `tag`     | `image tag`         | 给镜像打标签                |
| `top`     | `container top`     | 查看容器的运行进程             |
| `unpause` | `container unpause` | 恢复暂停的进程               |
| `update`  | `container update`  | 更新容器配置                |
| `version` | `version`           | 查看 Docker 版本信息        |
| `volume`  | `volume`            | 管理 Docker 卷           |
| `wait`    | `container wait`    | 阻塞等待容器停止，然后打印退出码      |

英文原文：http://blog.arungupta.me/docker-1-13-management-commands/
