---
layout: post
title: ceph简介
categories: [ceph]
description: some word here
keywords: ceph
---


## ceph简介
ceph是个分布式文件系统，使用C++编写，没有单点故障依赖； 其他常见的分布式文件系统有：GFS、HDFS、OpenStack Swift、 、mogileFS、TFS、FastDFS等。它们都不是系统级的分布式文件系统，而是应用级的分布式文件存储服务。

视频组对分布式文件系统的调研选型参考：
[视频存储调研总结](https://wiki.bytedance.com/pages/viewpage.action?pageId=51358629)

## 3个基本组件 
Ceph最底层的存储单元是Object对象，每个Object包含元数据和原始数据。

ceph架构图:
![](http://docs.ceph.com/docs/master/_images/stack.png)

最底层的RADOS就对应Ceph Storage Cluster，主要由3个组件组成： OSD、 Monitors、 MDS

上层是LIBRADOS，这可以当成是访问RADOS的一个库。用户可以利用这个库开发自己的客户端应用；
Ceph提供的对象存储（RADOSGW）、块设备（RBD）、文件系统（CEPHFS）也都是基于这个库完成的。


### OSD
ODS全称Object Storage  Device， 也就是负责响应客户端请求返回具体数据的进程。Ceph的OSD守护进程（OSD）存储数据，处理数据复制，恢复，回填，重新调整，并通过检查其它Ceph OSD守护程序作为一个心跳 向Ceph的监视器报告一些检测信息。一个Ceph集群一般都有很多个OSD。
多个OSD组成对象存储集群；Ceph的存储集群需要至少2个OSD守护进程来保持一个active状态；

### MDS
MDS全称Ceph Metadata Server，元数据服务器，是CephFS服务依赖的元数据服务。

### Monitors
Cluster monitors，集群监视器（执行监视功能); 一个Ceph集群需要多个Monitor组成的小集群，它们通过Paxos同步数据。


## 3个存储接口

![](http://docs.ceph.org.cn/_images/ditaa-a116a4a81d0472ef44d503c262528e6c1ea9d547.png)

ceph支持三种存储接口：

### 对象存储（RGW:RADOS gateway）
Ceph 对象存储服务提供了 REST 风格的 API ，它有与 Amazon S3 和 OpenStack Swift 兼容的接口。这个也就是通常意义的键值存储，其接口就是简单的GET、PUT、DEL和其他扩展，如七牛、又拍;

### 块存储（RBD：RADOS block device）
这种接口通常以QEMU Driver或者Kernel Module的方式存在，这种接口需要实现Linux的Block Device的接口或者QEMU提供的Block Driver接口，如Sheepdog，AWS的EBS，青云的云硬盘和阿里云的盘古系统，还有Ceph的RBD（RBD是Ceph面向块存储的接口）
Ceph 块设备服务提供了大小可调、精炼、支持快照和克隆。为提供高性能， Ceph 把块设备条带化到整个集群。 Ceph 同时支持直接使用 librbd 的内核对象（ KO ）和 QEMU 管理程序——避免了虚拟系统上的内核对象过载。

### 文件存储（CephFS：Ceph File System）
Ceph 文件系统服务提供了兼容 POSIX 的文件系统，可以直接挂载为用户空间文件系统。
它跟传统的文件系统如Ext4是一个类型，但区别在于分布式存储提供了并行化的能力，如Ceph的CephFS(CephFS是Ceph面向文件存储的接口)，但是有时候又会把GFS，HDFS这种非POSIX接口的类文件存储接口归入此类。

**官网上提示ceph的文件存储还不成熟，不适用与生产环境;**

注：除了以上3种存储接口， 还可以直接使用librados的原生接口，直接和RADOS通信；

## 3篇论文

Ceph是Sega本人的博士论文作品，想了解Ceph的架构设计最好的方式是阅读Sega的论文，其博士论文我们称之为长论文，后来整理成三篇较短的论文。

- CRUSH论文
  CRUSH是Ceph使用的数据分布算法，类似一致性哈希，让数据分配到预期的地方。
  CRUSH论文标题为《CRUSH: Controlled, Scalable, Decentralized Placement of Replicated Data》，地址 http://ceph.com/papers/weil-crush-sc06.pdf ，介绍了CRUSH的设计与实现细节。

- RADOS论文
  RADOS：Reliable Autonomic Distributed Object Store ， RADOS是Ceph集群的精华，用户实现数据分配、Failover等集群操作。
  RADOS论文标题为《RADOS: A Scalable, Reliable Storage Service for Petabyte-scale Storage Clusters》，地址为 http://ceph.com/papers/weil-rados-pdsw07.pdf ，介绍了RADOS的设计与实现细节。

- CephFS论文
  CephFS文件系统真实场景中使用的少，这个论文的重要性不及上述两篇；
  CephFS论文标题为《Ceph: A Scalable, High-Performance Distributed File System》，地址为 http://ceph.com/papers/weil-ceph-osdi06.pdf ，介绍了Ceph的基本架构和Ceph的设计与实现细节。


- 长论文
  长论文包含了RADOS、CRUSH等所有内容的介绍，感兴趣可以阅读，标题为《CEPH: RELIABLE, SCALABLE, AND HIGH-PERFORMANCE DISTRIBUTED STORAGE》，地址 http://ceph.com/papers/weil-thesis.pdf 。


## 参考

http://docs.ceph.com/docs/master
https://www.gitbook.com/book/tobegit3hub1/ceph_from_scratch/details

