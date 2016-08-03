---
layout: post
title: 通过源码安装ceph
categories: [ceph]
description: some word here
keywords: ceph, radosgw
---

# 通过源码安装ceph

从release 地址选定指定的ceph源码版本，eg：
v0.94.6.tar.gz

https://github.com/ceph/ceph/releases

ceph的版本看得有点乱，一会发布0.94，一会又发布10.2，得看看版本对照说明：
http://docs.ceph.org.cn/releases/
大致意思是从0.94开始，使用新的版本号，新版本号为9.0.0；

下载之后，make会报错：
No rule to make target `erasure-code/jerasure/jerasure/src/cauchy.c'

因为版本包中不包含依赖包
```shell
cd ceph-0.94.6/src/erasure-code/jerasure
git clone https://github.com/ceph/jerasure
git clone https://github.com/ceph/gf-complete
```

到相应目录下，先安装gf-complete
```shell

  ./configure
   make
   sudo make install
```

再安装jerasure
```shell
1.) Install GF-Complete
2.) autoreconf --force --install
3.) ./configure
4.) make
5.) sudo make install
```


这连个依赖组件安装完成之后，再回到ceph目录
```shell

./configure (如果之前执行过configure后make出错，需要重新执行这步操作)
make
```

