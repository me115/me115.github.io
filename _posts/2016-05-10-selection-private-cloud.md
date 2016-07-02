---
layout: post
title: 私有云调研及思考
categories: [docker, 虚拟化]
description: 
keywords: 虚拟化, 私有云, docker, Kubernetes, mesos
--- 


## 建设私有云的目的
1. 提高服务器利用率；
   现在：服务器利用率为了安全起见，都需要保持在50%以下；
   通过增加服务器利用率使其更高效，

2. 实现业务弹性、水平扩展；
   服务器利用率不高的主要原因是基于安全考虑，担心突发的流量对系统
   造成冲击；而如果能实现业务快速的水平扩展，就解决了这个后顾之忧；
   虚拟化之后，可以支持的服务快递的水平扩展；

3. 提升发布和部署的效率；
   发布和部署一个服务需要小流量，然后全流量；中间遇到问题需要方便的回滚；
   支持灰度发布；
   发布或扩容一个服务之后，能够方便而快速的让上层应用感知；

## 如何部署一个轻量化的私有云？
这里先考虑一下最基本的私有云建设需要哪些元素，再在这个基础上提升和完善；
考虑以下几点：
选择容器；
服务发现；
容器管理；

1）选择容器
容器选择docker，这个目前的主流和共识；在服务化完成后，将无状态的服务都封装在容器中；

2）服务发现
有了服务，需要一个服务发现的机制；即如何让上层能够发现服务；
可选择的有（Zookeeper、etcd、consul等）；
etcd、consul在我们内部都有使用；

3）容器管理
第三项也是最复杂的，就是容器管理；简单的说就是从统一的入口管理容器（构建容器、启动容器等）
Docker 有remote API，提供了相关的编程接口来从远程管理容器（构建容器、启动容器等）。
可以利用这写api来写一个管理工具（client/web），达到基本的容器管理的目的；
相应的，需要自行给加上监控工具，当出现问题时通知etcd；

更通用的是使用集群管理工具，可选的主要有Swarm、Kubernetes、Mesos；

Swarm：docker官方提供；Swarm是Docker在2014年12月份新发布的Docker集群管理工具。Swarm的优势是与Docker接口API统一；

Kubernetes：Google Borg/Omega的开源实现、更新迭代很快，架构及实现相对复杂；
作为Google开源的容器集群管理系统，使用Golang开发，其提供应用部署、维护、扩展机制等功能，利用Kubernetes能方便地管理跨机器运行容器化的应用，其主要功能如下：
使用Docker对应用程序包装(package)、实例化(instantiate)、运行(run)。
以集群的方式运行、管理跨机器的容器。
解决Docker跨机器容器之间的通讯问题。
Kubernetes的自我修复机制使得容器集群总是运行在用户期望的状态。

Mesos：Mesos是Apache下的开源分布式资源管理框架，它被称为是分布式系统的内核。Mesos最初是由加州大学伯克利分校的AMPLab开发的，后在Twitter得到广泛使用。；
介绍Mesos时，通常会介绍整个Mesos“堆栈”：如前所述，基于其两阶段调度特性，用户需要能够使用Mesos的“Mesos框架”（比如，Marathon，Aurora，Singularity），才能够像Kubernetes调度器那样工作。


## 对比主要的容器管理工具
以下将对比Kubernetes、mesos和swarm；

### 架构区别
Mesos 是支持多种调度器的，Docker 容器型的任务，Hadoop、Spark 的计算任务等都可以运行在 Mesos 框架上，Mesos 强调的是资源混用的能力； 
Kubernetes、Swarm只专注于 Docker 容器型任务。

### 水平扩展
Kubernetes、Mesos 与 Docker Swarm 都支持的向集群添加新的节点。

### 健康检查
Kubernetes能够自动管理和修复容器。
Swarm没有提供对其部署的容器进行健康检查的功能，所以需要容器部署方来进行相应的容器的健康检查以及异常重启等；
Mesos 的调度器 Marathon支持健康检查(需要应用提供 health 的接口)

### 语言比较
Kubernetes和Swarm使用Golang开发；
Mesos涉及到很多组件：Mesos（C++）、Marathon（Scala）、Mesos-DNS（Golang）等等。涉及到的语言较多；

### 成熟度
Kubernetes/swarm还很年轻，可能会遇到bug；
Mesos相对成熟，更容易找到使用其生产环境用例和最佳实践；

### 上手难易度
从易到难：swarm < Mesos < Kubernetes ；

## 业界的选择
### 国外
Kubernetes由Google发起，现在得到了如微软、红帽、IBM和Docker等众多厂商的支持。
Google使用Borg进行大规模集群的管理，而Kubernetes是Google Borg/Omega的开源实现；

Mesos管理着Twitter超过30,0000台服务器上的应用部署。
Apple现在使用Mesos作为成千个服务器节点的资源调度器来支持Siri服务（75000个节点）；

### 国内
![](images/blog/docker-use-1.jpg)


