---
layout: post
title: Docker 常用命令
categories: Docker
description: docker 常用命令
keywords: Docker, 
---


## 操作容器

### 启动容器

- 启动容器并启动bash（交互方式）:

```shell
$docker run -i -t <image_name/continar_id> /bin/bash
```

- 启动容器以后台方式运行(更通用的方式）：

```shell
$docker run -d -it  image_name
```
ps：这里的 image_name 包含了tag：hello.demo.kdemo:v1.0


- 启动容器并做端口映射

```shell
# Bind TCP port 8080 of the container to TCP port 80 on 127.0.0.1 of the # host machine. 
# -p  <host_ip>:<container_ip>
$docker run -p 127.0.0.1:80:8080 <image> <cmd>
```


### 附着到容器

- 附着到正在运行的容器

```shell
docker attach <id、container_name>
```

- 进入正在运行的容器内部，同时运行bash(比attach更好用)

```shell
docker exec -t -i <id/container_name>  /bin/bash
```

ps：docker exec是如此的有用，以至于我们通常是将其封装为一个脚本，放到全局可调用的地方，比如，可以写成一个indocker.sh：

```shell
$cat indocker.sh 
docker exec -t -i $1 /bin/bash
# 查看需要附着的容器id
$docker ps | less -S
CONTAINER ID        IMAGE                                                 
9cf7b563f689        hello.demo.kdemo:v160525.202747

$./indocker.sh 9cf7b563f689 
```

### 查看容器日志

- 查看容器日志

```shell
docker logs <id/container_name>
```

- 实时查看日志输出

```shell
docker logs -f <id/container_name> (类似 tail -f) (带上时间戳-t）
```

### 查看容器

- 列出当前所有正在运行的container  

```shell
$docker ps
```

- 用一行列出所有正在运行的container（容器多的时候非常清晰）

```shell
$docker ps | less -S
```

- 列出所有的container  

```shell
$docker ps -a  
```

- 列出最近一次启动的container  

```shell
$docker ps -l 
```

- 显示一个运行的容器里面的进程信息  

```shell
$docker top Name/ID  
```

- 查看容器内部详情细节：

```shell
$docker inspect <id/container_name>
```

- 在容器中安装新的程序  

```shell
$docker run image_name apt-get install -y app_name  
```
> Note：  在执行apt-get 命令的时候，要带上-y参数。如果不指定-y参数的话，apt-get命令会进入交互模式，需要用户输入命令来进行确认，但在docker环境中是无法响应这种交互的。apt-get 命令执行完毕之后，容器就会停止，但对容器的改动不会丢失。

- 从容器里面拷贝文件/目录到本地一个路径  

```shell
$docker cp Name:/container_path to_path  
$docker cp ID:/container_path to_path
```

- 保存对容器的修改（commit）

当你对某一个容器做了修改之后（通过在容器中运行某一个命令），可以把对容器的修改保存下来，这样下次可以从保存后的最新状态运行该容器。

```shell
$docker commit ID new_image_name  
```

> Note：  image相当于类，container相当于实例，不过可以动态给实例安装新软件，然后把这个container用commit命令固化成一个image。


- 删除单个容器

```shell
$docker rm Name/ID 
```

>  -f, –force=false; -l, –link=false Remove the specified link and not the underlying container; -v, –volumes=false Remove the volumes associated to the container

- 删除所有容器  

```shell
$docker rm `docker ps -a -q`  
```

- 停止、启动、杀死、重启一个容器  

```shell
$docker stop Name/ID  
$docker start Name/ID  
$docker kill Name/ID  
$docker restart name/ID
```


## 操作Image

- 列出镜像

```shell
$sudo docker images
```

> -a, –all=false Show all images; –no-trunc=false Don’t truncate output; -q, –quiet=false Only show numeric IDs  

- 从dockerhub检索image  

```shell
$docker search image_name
```

- 下载image  

```shell
$docker pull image_name
```

- 删除一个或者多个镜像;   

```shell
$docker rmi image_name  
```

> -f, –force=false Force; –no-prune=false Do not delete untagged parents

- 显示一个镜像的历史;

```shell
$docker history image_name
```

- 发布docker镜像

```shell
$docker push new_image_name
```

ps:要发布到私有Registry中的镜像，在镜像命名中需要带上Registry的域名（如果非80端口，同时需要带上端口号）比如：

```shell
$docker push dockerhub.yourdomain.com:443/hello.demo.kdemo:v1.0
```

- 拉取docker镜像

```shell
$docker pull image_name
```

## 网络操作

- 查看docker0的网络(宿主机上操作)

```shell
$ip a show docker0
```

- 查看容器的IP地址

```shell
$docker inspect -f '{{ .NetworkSettings.IPAddress }}' <id、container_name>
```

附着到容器内部查看其内部ip：

```shell
$ip a show eth0
```

## 查看docker基础信息

### 查看docker版本  

```shell
$docker version  
```

### 查看docker系统的信息  

```shell
$docker info
```

Posted by: 大CC | 29MAY,2016
博客：[blog.me115.com](http://blog.me115.com) [[订阅](http://blog.me115.com/feed)]
Github：[大CC](https://github.com/me115)



