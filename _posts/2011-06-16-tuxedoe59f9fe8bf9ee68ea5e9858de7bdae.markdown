---
author: me115wp
comments: true
date: 2011-06-16 09:16:46+00:00
layout: post
link: http://blog.me115.com/2011/06/156
slug: tuxedo%e5%9f%9f%e8%bf%9e%e6%8e%a5%e9%85%8d%e7%bd%ae
title: Tuxedo域连接配置
wordpress_id: 156
categories:
- C++编程
tags:
- Tuxedo
---

当多个域之间有Tuxedo服务调用关系，必须通过域连接才能实现正常调用。

 

通过以下步骤完成连接配置：

 

**1 导出**

 

首先，需要导出当前的域连接信息。通过管道将信息导入到一个文件中，当然，编辑钱不忘备份：

 

dmunloadcf > dm.0616;

 

cp dm.0616 dm.0616.bak;

 

 

**_2 dm格式说明_**

 

dm文件中主要有以下几个部分：

 

*DM_LOCAL 

 

"ABC-25" GWGRP="TDMGRP1"      
ACCESSPOINTID="ABC-25"       
BLOCKTIME=10       
DMTLOGDEV="/opt/app/tuxapp/log/DLOG"       
DMTLOGNAME="DMTLOG"       
MAXRACCESSPOINT=89       
MAXTRAN=100       
BLOB_SHM_SIZE=1000000

 

DM_LOCAL 块是本地域配置信息；

 

 

*DM_REMOTE 

 

"DEF55" ACCESSPOINTID="DEF55"      
CREDENTIAL_POLICY="LOCAL"       
DM_REMOTE 块里是远程域配置信息；

 

 

*DM_TDOMAIN 

 

"ABC-25" NWADDR="//10.6.***.25:7830"

 

"DEF55" NWADDR="//10.6.***.36:6666"      
LACCESSPOINT="ABC-25"

 

DM_TDOMAIN里是本地与远程的域互联的服务器地址及端口；

 

 

*DM_EXPORT

 

"SERVICE1" COUPLING=LOOSE 

 

"SERVICE2" COUPLING=LOOSE

 

DM_EXPORT中配置是对外公布的域导出服务，即与本地域连接上的远程域中的机器可调用的服务；

 

*DM_IMPORT中配置的是导入服务，即本地域需要访问远程域中的服务名；

 

 

**_3.编辑_**

 

编辑这个文件，在相应的块中添加我们需要配置的域连接信息；

 

注意，如果我们的本地的域命名中带有字符 - ，将这个导出的文件不做任何修改直接导入也会提示错误信息。

 

导出的文件在*DM_IMPORT段中LACCESSPOINT=ABC-25 提示出错；

 

解决的方法有2种：1是采用另外一个dm导出格式，比较麻烦，这里不做说明；

 

2是修改导出文件，将LACCESSPOINT=ABC-25中的域名用引号引起来：

 

LACCESSPOINT="AVE-25"

 

 

**_4 导入_**

 

编辑完成后就可以导入了，导入前需要将Tuxedo服务停止，就跟load修改的ubb一样。当然，也可以动态的配置域连接，方法需配置脚本，感兴趣的可自行上网搜索。

 

tmshutdown –y

 

dmloadcf –y dm.0616

 

如果没有提示出错，则导入成功；

 

启动服务之后，与域的另一方协商，双方都配置完成之后即可实现域连接。

 

**_5 管理_**

 

_4.1 查看本机已经连上的域_

 

dmadmin

 

>pd –d 本机域名：

 

eg： pd –d ABC-25 

 

可查看与本机成功连接的域；

 

_4.2 强制连接_

 

当双方都已配置完成之后，可通过以下命令实现强制连接：

 

dmadmin

 

>co –d 本机域名 –R 远程域名

 

eg:> co -d ABC-25 -R DEF55

 

OVER!
