---
layout: wiki
title: Redis 命令
categories: [redis]
description: some word here
keywords: keyword1, keyword2
---

## redis 命令及配置
### 常用命令查询
[Redis命令参考](http://redisdoc.com/)
[redis info 状态说明](http://redisdoc.com/server/info.html)

### 主从设置
```shell
slaveof 10.4.44.71 3904
slaveof no one
# 设置从可写 /不可写
config set slave-read-only yes
config set slave-read-only no 
```

### 运维工具
查看当前的连接到服务器的客户端的列表：
```shell
client list
127.0.0.1:6379> client list
addr=127.0.0.1:52555 fd=5 name= age=855 idle=0 flags=N db=0 sub=0 psub=0 multi=-1 qbuf=0 qbuf-free=32768 obl=0 oll=0 omem=0 events=r cmd=client
addr=127.0.0.1:52787 fd=6 name= age=6 idle=5 flags=N db=0 sub=0 psub=0 multi=-1 qbuf=0 qbuf-free=0 obl=0 oll=0 omem=0 events=r cmd=ping
```
每一行表示一个连接的各项信息：

addr: 客户端的TCP地址，包括IP和端口
fd: 客户端连接 socket 对应的文件描述符句柄号
name: 连接的名字，默认为空，可以通过 CLIENT SETNAME 设置
age: 客户端存活的秒数
idle: 客户端空闲的秒数
flags: 客户端的类型  (N 表示普通客户端，更多类型见 http://redis.io/commands/client-list)
omem: 输出缓冲区的大小
cmd: 最后执行的命令名称

查看当前正在执行的命令：
```shell
>monitor
```

## 

## redis cluster

### 查看 cluster nodes 拓扑分布
```shell
redis-cli -h 10.3.23.27 -p 3920 cluster nodes
```

### 将一个新节点加入到现有集群中
选择原有集群中的任意一个节点（10.3.23.35:3920），加入新的节点：10.3.20.213:3920

```shell
redis-cli -h 10.3.23.35 -p 3920 CLUSTER MEET 10.3.20.213 3920
```

### 设置从节点
设置10.3.20.213 3920 为 node-id （fd276dc8e818f53239f988dc2bb3ec85504f9096）的从节点：

```shell
redis-cli -h 10.3.20.213 -p 3920
>CLUSTER REPLICATE fd276dc8e818f53239f988dc2bb3ec85504f9096
```

### 从集群中重置一个节点
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

### 查看集群中指定slot 中的key的数量

```shell
$redis-cli -h 10.3.23.35  -p 3920 CLUSTER COUNTKEYSINSLOT 11587
```

### 设置 slot 在特定节点上
```shell
$ redis-cli -h 10.3.21.50  -p 3920 CLUSTER SETSLOT  11587 NODE  f68aa3484f2340a9586632b2696f5a8fe0b30f40
```

### 迁移 slots

```shell
$ ./redis-trib.rb reshard 10.3.23.27:3920
```

ref: [https://redis.io/commands/cluster-setslot](https://redis.io/commands/cluster-setslot)

### cluster failover
主从都为存活状态，将 slave 切换为 master：
```shell
slave-node-cli> CLUSTER FAILOVER
```

master 挂掉，将 slave 切换为 master：
```shell
slave-node-cli>CLUSTER FAILOVER [FORCE|TAKEOVER]
```
FORCE选项： 不与master握手，广播信息，与其集群其它 master 通信，获取授权投票成为新的master；
TAKEOVER选项：不与 master 握手，也不需获取其他 master 投票通过，直接提升为master （应用场景eg：数据中心切换时将大量的slave 设置为 master ，但此时 大多数 master都不可用，或者发生了脑裂）

注：TAKEOVER violates the last-failover-wins principle of Redis Cluster


###  slot 迁移过程

1. 设置目标节点为  importing state：
```shell
    CLUSTER SETSLOT <slot> IMPORTING <source-node-id>.
```
2. 设置源节点为 migrating state :  
```shell
CLUSTER SETSLOT <slot> MIGRATING <destination-node-id>.
```

3. 从源节点循环读取出key , 然后将他们转移到目标节点：
```shell
CLUSTER GETKEYSINSLOT
MIGRATE host port key "" destination-db timeout REPLACE KEYS key [key ...]]
```
4. 设置slots状态为正常：
```shell
 CLUSTER SETSLOT <slot> NODE <destination-node-id> # in the source or destination 
```

注：如果迁移过程中断，修改状态，对带修复的节点执行：
```shell
redis-cli  -h <host> -p <port> cluster setslot <slot-num> stable
```

## 配置
### 内存回收策略
当maxmemory限制到达的时候，Redis将采取的准确行为是由maxmemory-policy配置指令配置的。
以下策略可用：
- noeviction：当到达内存限制时返回错误。当客户端尝试执行命令时会导致更多内存占用(大多数写命令，除了DEL和一些例外)。
- allkeys-lru：回收最近最少使用(LRU)的键，为新数据腾出空间。
- volatile-lru：回收最近最少使用(LRU)的键，但是只回收有设置过期的键，为新数据腾出空间。
- allkeys-random：回收随机的键，为新数据腾出空间。
- volatile-random：回收随机的键，但是只回收有设置过期的键，为新数据腾出空间。
- volatile-ttl：回收有设置过期的键，尝试先回收离TTL最短时间的键，为新数据腾出空间。

