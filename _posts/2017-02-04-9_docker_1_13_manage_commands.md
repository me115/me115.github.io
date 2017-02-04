---
layout: post
title: Docker 1.13 管理命令
categories: [docker]
description: some word here
keywords: docker, docker cli
---

# Docker 1.13 管理命令

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
| 1.12      | 1.13                | PURPOSE                                  |
| --------- | ------------------- | ---------------------------------------- |
| `attach`  | `container attach`  | Attach to a running container            |
| `build`   | `image build`       | Build an image from a Dockerfile         |
| `commit`  | `container commit`  | Create a new image from a container’s changes |
| `cp`      | `container cp`      | Copy files/folders between a container and the local filesystem |
| `create`  | `container create`  | Create a new container                   |
| `diff`    | `container diff`    | Inspect changes on a container’s filesystem |
| `events`  | `system events`     | Get real time events from the server     |
| `exec`    | `container exec`    | Run a command in a running container     |
| `export`  | `container export`  | Export a container’s filesystem as a tar archive |
| `history` | `image history`     | Show the history of an image             |
| `images`  | `image ls`          | List images                              |
| `import`  | `image import`      | Import the contents from a tarball to create a filesystem image |
| `info`    | `system info`       | Display system-wide information          |
| `inspect` | `container inspect` | Return low-level information on a container, image or task |
| `kill`    | `container kill`    | Kill one or more running containers      |
| `load`    | `image load`        | Load an image from a tar archive or STDIN |
| `login`   | `login`             | Log in to a Docker registry.             |
| `logout`  | `logout`            | Log out from a Docker registry.          |
| `logs`    | `container logs`    | Fetch the logs of a container            |
| `network` | `network`           | Manage Docker networks                   |
| `node`    | `node`              | Manage Docker Swarm nodes                |
| `pause`   | `container pause`   | Pause all processes within one or more containers |
| `port`    | `container port`    | List port mappings or a specific mapping for the container |
| `ps`      | `container ls`      | List containers                          |
| `pull`    | `image pull`        | Pull an image or a repository from a registry |
| `push`    | `image push`        | Push an image or a repository to a registry |
| `rename`  | `container rename`  | Rename a container                       |
| `restart` | `container restart` | Restart a container                      |
| `rm`      | `container rm`      | Remove one or more containers            |
| `rmi`     | `image rm`          | Remove one or more images                |
| `run`     | `container run`     | Run a command in a new container         |
| `save`    | `image save`        | Save one or more images to a tar archive (streamed to STDOUT by default) |
| `search`  | `search`            | Search the Docker Hub for images         |
| `service` | `service`           | Manage Docker services                   |
| `start`   | `container start`   | Start one or more stopped containers     |
| `stats`   | `container stats`   | Display a live stream of container(s) resource usage statistics |
| `stop`    | `container stop`    | Stop one or more running containers      |
| `swarm`   | `swarm`             | Manage Docker Swarm                      |
| `tag`     | `image tag`         | Tag an image into a repository           |
| `top`     | `container top`     | Display the running processes of a container |
| `unpause` | `container unpause` | Unpause all processes within one or more containers |
| `update`  | `container update`  | Update configuration of one or more containers |
| `version` | `version`           | Show the Docker version information      |
| `volume`  | `volume`            | Manage Docker volumes                    |
| `wait`    | `container wait`    | Block until a container stops, then print its exit code |

英文原文：http://blog.arungupta.me/docker-1-13-management-commands/
