---
layout: post
title: ceph集群管理及客户端访问示例
categories: [ceph]
description: some word here
keywords: ceph
---

# ceph集群管理及客户端访问示例

我们使用的是ceph的对象存储，对象存储的访问方式有两种：

1. 直接使用librados访问；
2. 通过ceph对象网关（RGW）访问；

## 建立ceph master

### 安装
安装一个ceph比较复杂，需要装的组件比较多；如果是学习使用，使用docker镜像是个不错的选择：

以下这个ceph的docker镜像安装完整的ceph组件，包括cephgw网关；

https://github.com/ceph/ceph-docker

(物理机安装详见官网步骤：http://docs.ceph.com/docs/master/start/)

这个docker镜像启动前，需要传入宿主机的ip（10.6.26.137）；

网络使用host模式，启动镜像：
docker run -d -it --net=host -v /etc/ceph:/etc/ceph -v /opt/ceph:/var/lib/ceph -e MON_
IP=10.6.26.137 -e CEPH_PUBLIC_NETWORK=10.6.26.0/24

配置文件存放在 /etc/ceph 目录；
数据文件存放在 /var/lib/ceph 目录；

### 查看集群状态

watch整个ceph集群的状态：ceph -w (或ceph status)
```shell
root@n6-026-137:/# ceph -w
    cluster edecce37-314e-46d0-99df-ca649aef29dc
     health HEALTH_OK
     monmap e1: 1 mons at {n6-026-137=10.6.26.137:6789/0}
            election epoch 3, quorum 0 n6-026-137
      fsmap e5: 1/1/1 up {0=0=up:active}
     osdmap e26: 1 osds: 1 up, 1 in
            flags sortbitwise
      pgmap v47227: 280 pgs, 13 pools, 138 MB data, 219 objects
            47077 MB used, 824 GB / 916 GB avail
                 280 active+clean

2016-07-23 08:14:32.941256 mon.0 [INF] pgmap v47227: 280 pgs: 280 active+clean; 138 MB data, 47077 MB used, 824 GB / 916 GB avail
```

### 操作存储池

存储池是存储对象的逻辑分区，客户端要存储数据需要指定并打开相应的存储池;

#### 创建存储池(pool)
创建一个名为colin-data的存储池
```shell
ceph osd pool create colin-data 128 128
pool 'colin-data' created

```

#### 查看系统所有的pools
```shell
root@n6-026-137:~# rados lspools
rbd
cephfs_data
cephfs_metadata
.rgw.root
default.rgw.control
default.rgw.data.root
default.rgw.gc
default.rgw.log
colin-data
```

#### 查看pools的容量
```shell
root@n6-026-137:~# rados df
pool name                 KB      objects       clones     degraded      unfound           rd        rd KB           wr        wr KB
.rgw.root                  2            4            0            0            0           48           38            4            5
cephfs_data                0            0            0            0            0            0            0            0            0
cephfs_metadata            3           20            0            0            0            0            0           41            7
colin-data                 0            0            0            0            0            0            0            0            0
default.rgw.control            0            8            0            0            0            0            0            0            0
default.rgw.data.root            0            0            0            0            0            0            0            0            0
default.rgw.gc             0           32            0            0            0          384          352          256            0
default.rgw.log            0          127            0            0            0         7620         7493         5080            0
rbd                        0            0            0            0            0            0            0            0            0
  total used        44510392          191
  total avail      867937016
  total space      961301832
```

## 查看指定pool中的数据
```shell
root@n6-026-137:/# rados -p colin-data ls
ceph:toutiao.conf_1.0.0.2.tar_md5
ceph:service_rpc.tool_1.0.0.1.tar_md5
ceph:toutiao.lib.toutiao_1.0.0.1.tar_md5
ceph:toutiao.monitor.service_1.0.0.1.tar_md5
ceph:toutiao.frame_1.0.0.2.tar
ceph:toutiao.runtime_1.0.0.2.tar_md5
ceph:demo.kdemo_3.1.0.38.tar
ceph:service_rpc.tool_1.0.0.1.tar
ceph:toutiao.monitor.common_1.0.0.1.tar_md5
ceph:service_rpc.idl_1.0.0.1.tar_md5
ceph:demo.kdemo_3.1.0.38.tar_md5
ceph:toutiao.conf_1.0.0.2.tar
```

## client-直接使用librados访问

以下介绍python直接通过librados访问ceph；

### 安装librados
```shell
sudo apt-get install python-rados
```

### 配置文件
客户端访问需要两个文件, 在/etc/ceph下都有；
ceph.conf：配置文件
ceph.client.admin.keyring：授权的加密key

客户端读取配置文件之后和服务建立连接：
![](http://docs.ceph.com/docs/master/_images/ditaa-bd1070de50515485f116a874684956bc7e26c664.png)


### 完整流程（代码示例）

一个完整的客户端书写数据的流程：

1. 读去配置文件，建立连接；
2. 打开IO上下文（存储池）；
3. 读写数据；
4. 关闭上下文；
5. 关闭连接；

![](http://docs.ceph.com/docs/master/_images/ditaa-186075203a47d104deed70373affeb15ec7dc9c8.png)

ceph提供了以下语言的客户端支持：c、c++、python、java、php；

去掉异常代码，python代码逻辑可以简化为：
```shell
import rados

cluster = rados.Rados(conffile='')
cluster.connect()
print "Connected to the cluster."

print "\nCreating a context for the 'data' pool"
if not cluster.pool_exists('data'):
        raise RuntimeError('No data pool exists')
ioctx = cluster.open_ioctx('data')

print "\nWriting object 'hw' with contents 'Hello World!' to pool 'data'."
ioctx.write("hw", "Hello World!")

print "\nContents of object 'hw'\n------------------------"
print ioctx.read("hw")

print "\nRemoving object 'hw'"
ioctx.remove_object("hw")

print "\nClosing the connection."
ioctx.close()

print "Shutting down the handle."
cluster.shutdown()

```

## 参考
http://docs.ceph.com/docs/master/rados/api/librados-intro/


