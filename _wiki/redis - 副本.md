---
layout: wiki
title: Redis Cluster 命令
categories: [redis]
description: some word here
keywords: keyword1, keyword2
---


## 查看 cluster nodes 拓扑分布
```shell
redis-cli -h 10.3.23.27 -p 3920 cluster nodes
```

## 将一个新节点加入到现有集群中
选择原有集群中的任意一个节点（10.3.23.35:3920），加入新的节点：10.3.20.213:3920

```shell
redis-cli -h 10.3.23.35 -p 3920 CLUSTER MEET 10.3.20.213 3920
```

## 设置从节点
设置10.3.20.213 3920 为 node-id （fd276dc8e818f53239f988dc2bb3ec85504f9096）的从节点：

```shell
redis-cli -h 10.3.20.213 -p 3920
>CLUSTER REPLICATE fd276dc8e818f53239f988dc2bb3ec85504f9096
```

## 从集群中重置一个节点
首先从集群拓扑中删除该节点，然后清空重置该节点数据，最后再加入到集群中；
eg: 要操作的节点 为10.3.20.213:3920 ，其node-id为fd276dc8e818f53239f988dc2bb3ec85504f9096

```shell
# 对集群中所有节点发送forget 该节点的命令
$redis-cli -h 10.3.1.1 -p 3920 
>CLUSTER FORGET fd276dc8e818f53239f988dc2bb3ec85504f9096
# 连接该节点，清空数据并重置
$redis-cli -h 10.3.20.213 -p 3920
> FLUSHALL
>CLUSTER RESET
# 连接任意一个节点，将新节点加入：
redis-cli -h 10.3.1.1 -p 3920 CLUSTER MEET 10.3.20.213 3920
```

## 查看集群中指定slot 中的key的数量

```shell
$redis-cli -h 10.3.23.35  -p 3920 CLUSTER COUNTKEYSINSLOT 11587
```

## 设置 slot 在特定节点上
```shell
$ redis-cli -h 10.3.21.50  -p 3920 CLUSTER SETSLOT  11587 NODE  f68aa3484f2340a9586632b2696f5a8fe0b30f40
```

## 迁移 slots

```shell
$ ./redis-trib.rb reshard 10.3.23.27:3920
```

ref: [https://redis.io/commands/cluster-setslot](https://redis.io/commands/cluster-setslot)