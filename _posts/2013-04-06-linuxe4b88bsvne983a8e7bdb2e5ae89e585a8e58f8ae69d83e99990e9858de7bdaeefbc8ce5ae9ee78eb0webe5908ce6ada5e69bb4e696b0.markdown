---
author: me115wp
comments: true
date: 2013-04-06 03:01:51+00:00
layout: post
link: http://blog.me115.com/2013/04/306
slug: linux%e4%b8%8bsvn%e9%83%a8%e7%bd%b2%e5%ae%89%e5%85%a8%e5%8f%8a%e6%9d%83%e9%99%90%e9%85%8d%e7%bd%ae%ef%bc%8c%e5%ae%9e%e7%8e%b0web%e5%90%8c%e6%ad%a5%e6%9b%b4%e6%96%b0
title: Linux下SVN部署/安全及权限配置，实现web同步更新
wordpress_id: 306
categories:
- 项目管理
tags:
- svn
- 项目管理
---

本文包含以下内容：  

SVN服务器安装

SVN权限管理

SVN使用SASL加密

SVN上传时同步其它目录

# **需求**

在WEB线上版本管理的基础上，能够在代码上传之后，立刻通过WEB访问，查看修改效果；同时，保证数据的安全性；

# **SVN安装**

SVN服务器有2种运行方式：独立服务器和借助apache运行。

svnserve和apache相比是轻量级的，也比较简单，svnserve包含在subversion里面，所以只要安装了subversion就相当于安装了一个小型的svn服务器。它使用自己的一套协议通信。例如访问apache时使用 http:// 前缀，而svnserve使用 svn:// 前缀.

这里介绍的是通过独立服务器方式运行，优点是简单小巧。如果是支持较大规模的开发，还是推荐使用apache服务器方式；这里使用svnserver安装；

1.首先为SVN单独创建一个用户，这样可以使用操作系统的安全特性；

2.将svnadmin这个用户加入到sudu组；

3.安装：

sudo apt-get install subversion

4.创建测试目录： 

mkdir /home/svnadmin/test

5.创建版本库：  
svnadmin create /home/svnadmin/test

4. 导入项目

例如现在有个工程，名称为examPro，

位置/alidata/www/examPro , 将这个工程导入到本地仓库中。  


svn import /alidata/www/examPro file:///home/svnadmin/examPro -m "import examPro"

在上一个例子里，将会拷贝目录examPro到版本库中；

这样这个工程就在纳入服务器的本地仓库管理。

为了使用SVN的同步更新机制，我们需要在svn服务器环境上签出一份最新工程拷贝（为表述方便，这里称为A目录）。  
$ svn checkout [[file:///home/svnadmin/examPro](file:///home/svnadmin/examPro)](file:///home/myrepos)  
在这个拷贝里，我们一般不做修改，而是用来同步更新；当开发机器上有任何修改，更新到SVN服务器上时，能同步更新到A目录；这样，就能保证A目录下的代码是整个工程的最新代码，而使用A目录搭建的WEB测试环境，就是最新的WEB测试环境；

5.启动SVN服务

$ svnserve -d -r /home/svnadmin/examPro

描述说明：  
-d 表示svnserver以“守护”进程模式运行  
-r 指定文件系统的根位置（版本库的根目录），这样客户端不用输入全路径，就可以访问版本库。

6.停止svn服务：

killall svnserve //停止svnserve服务

****

# **SVN权限管理**

## 1.SVN版本库目录说明

db：存放具体数据；  

hooks：钩子程序存放地，比如我们要实现同步更新的操作，在这里实现；  

conf：配置文件存放地  

下面具体说说conf目录；

conf目录下有三个文件：

svnserve.conf、authz、以及passwd；  


## 2.SVN服务配置文件：svnserve.conf

查看该文件，首先是匿名用户的权限配置：

anon-access = none

auth-access = write

表示：对于匿名用户，无访问权限；

对于授权用户，有写权限；

接下来的一段用于配置使用哪种授权登录方式；

可选的有password-db ，就是用户名和密码都是明文存放在同级目录下的passwd文件中；优点是高效配置简单，缺点是安全性弱，明文总不是那么让人感觉可靠；

另一种是authz-db，这种方式的用户密码使用了sasl加密，安全上有保证；

选择这种方式的设置，将password-db 注释掉：

# password-db = passwd

authz-db = authz

#指定授权所属的域，C++的同志可将其理解为名字空间；

realm = examPro

接下来是[sasl]段，用于标识是否进行SASL加密处理；

use-sasl = true

min-encryption = 128

max-encryption = 256

变量 min-encryption 和 max-encryption 控制服务器所需要的加密强度。

## 3. 详细权限配置文件authz：

这个就是授权数据库，用于配置指定目录对用户的访问权限；

首先是指定一个用户组，按组来分配权限总是方便的，即使目前你的团队一个组只有一个人。在新加入成员的时候，你就能体会到按组分配权限的便利性了；

[groups]

g_fronter=cuicc,gdii

g_vip=coo

g_doc=yhh

[examPro:/]

@g_vip=rw

@g_fronter=r

@g_doc=r

[examPro:/protected/modules]

@g_vip=rw

@g_fronter=

*=

[examPro:/protected]

@g_doc=

对于以上代码的配置的详细说明，可以参考本文的参考文章[1]SVN权限配置，里面介绍的比较详细，这里就不多说，有疑问的请留言或mail；

****

# **SVN使用SASL加密**

1.配置svnserve.conf，注释掉password-db = passwd

并启用sqsl：use-sasl = true

2.新建一个svn.conf文件，一般放在/usr/lib/sasl2或者/etc/sasl2，内容为：

pwcheck_method: auxprop

auxprop_plugin: sasldb

sasldb_path: /home/svnadmin/config/sasldb

mech_list: DIGEST-MD5

其中sasldb_path 指定你打算将sasl加密的数据库放置的位置；

注释：pwcheck_method指明检查的方法，这里是“auxprop ”，这个pwcheck_method还对应了如启动一个代理作为认证服务等方式，而现在的意思就是使用本文件说的方式去检查。然后我们指明auxprop_plugin为sasldb，也就是使用一个文件存放用户名密码，也就是/home/svn/svnjiami/sasldb,其它的认证信息存放plugin还有sql和ldapdb。而mech_list指明了认证信息传递机制。

svnserve 已经在运行，你需要重启服务，并确保它读取了更新后的配置参数

killall svnserve //停止svnserve服务

svnserve –d –r /home/svn //启动svnserve服务

3.创建用户和密码

使用saslpasswd2 程序

语法：saslpasswd2 –c –f /home/svn/jiami/sasldb –u [svnserve.conf里面配置的realm名字] [username]

eg：saslpasswd2 -c -f /home/svnadmin/config/sasldb -u examPro colin

会出现交互界面，提示输入两次密码；

附：

saslpasswd2 -d -f home/svnadmin/config/sasldb -u 用户名//删除用户

sasldblistusers2 -f /home/svnadmin/config/sasldb // 查询用户

PS：如果访问库的时候出现以下提示 "Could not obtain the list of SASL mechanisms"，原因是Linux默认没有安装DIGEST-MD5，此时，可用以下命令安装更新：yum install cyrus-sasl-md5 , cyrus-sasl-md5首页: [http://asg.web.cmu.edu/sasl/](http://asg.web.cmu.edu/sasl/), 安装包下载地址:[ftp://ftp.andrew.cmu.edu/pub/cyrus/](ftp://ftp.andrew.cmu.edu/pub/cyrus/)

配置完成；

**

# SVN上传时同步到服务器其它目录

**

svn/examPro/hooks/目录下：

能看到一堆模版钩子文件，我们需要的是post-commit.tmpl，

copy一份，命名为post-commit。然后修改；

1.设置语言环境：

#这行比较重要，需要根据你的服务器环境选择正确的语言环境，否则，这个update不会工作；

#export LANG=zh_CN.UTF-8

export LANG=en_US.UTF-8

2.设置SVN更新时需要同步更新的目录

svn update /alidata/www/examplePro --username yoursname --password yourpwd

以上这行表明，当svn服务器版本有更新时，则同步更新到/alidata/www/examPro目录下的对应文件；yoursname 和yourpwd是你在sasl中设置的用户名和密码；（这里还是涉及到了明文存放，对此，你可以分配一个用户对svn版本库只有全局的读权限）

3.输出日志，便于跟踪查询

echo `whoami`,$REPOS,$REV >> /home/svnadmin/examPro/hooks/svn_hook.log

每当有更新时，更新日志里就会插入一条语句，表明何时有过更新；（这个也可用来配置环境时调试，用来查询同步是否起效）

我们的配置到这里就全部完成；

现在，SVN就实现了版本管理的功能，同时，同步更新的目录有利于测试人员集成测试；

开发人员需要保证的是，任何时候签入到svn库中的版本是可运行的代码。

# 参考：

[1]:SVN权限配置：[http://hi.baidu.com/victorlin23/item/f3f42f276e9c810a42634a94](http://hi.baidu.com/victorlin23/item/f3f42f276e9c810a42634a94)

[2]:SVN使用SASL加密：[http://www.cnblogs.com/linn/archive/2011/08/04/2127014.html](http://www.cnblogs.com/linn/archive/2011/08/04/2127014.html)

[](http://www.cnblogs.com/linn/archive/2011/08/04/2127014.html)
