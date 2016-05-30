---
author: me115wp
comments: true
date: 2010-11-27 16:10:26+00:00
layout: post
link: http://blog.me115.com/2010/11/54
slug: bug-tacker-net-%e7%bc%ba%e9%99%b7%e8%b7%9f%e8%b8%aa%e5%bc%80%e6%ba%90%e7%b3%bb%e7%bb%9f-%e5%ae%89%e8%a3%85-%e4%bd%bf%e7%94%a8%e6%8c%87%e5%8d%97
title: Bug Tacker .Net 缺陷跟踪开源系统 安装 使用指南
wordpress_id: 54
categories:
- WEB开发
tags:
- Bug Tracker
- 安装
- 缺陷跟踪
---

**BugTracker.NET**是一个采用.NET开发的免费、开源的基于web的缺陷跟踪平台。

 

安装需求：       
系统配置        
aspx.net 2.0         
Sql server2005.(不推荐使用mssql 2008）； 

 

下载下来的包不大，也就2M，解压到一个文件夹中：（eg：c:bt)，为该文件夹设置web访问权限。建立iis到c:btwww的虚拟目录；按照常规设置即可； 

 

之后，建立一个数据库，可自己命名，为该数据库设置一个可以访问的用户；在btwww下的web.config中修改连接字符串： 

 

给的比较隐蔽，需要好好找找,以下是本机配置：       
<add key="ConnectionString" value="Data Source=pc-383;Initial Catalog=bugt;User ID=sa;Password=@d@@@33d"/>

 

配置完毕后，就可以启动IIS中的网站，浏览default.aspx；进入页面后，会提示没有建立表，点击链接下载建表脚本，放到MSSQL中执行一下；

 

整个项目搭建就算完成。

 

顺利的话，就可以通过default.aspx登录了；默认用户名和密码是 admin ;

 

更多安装细节可参考其官网介绍：[http://ifdefined.com/readme.html#installation](http://ifdefined.com/readme.html#installation)

 

 

使用比较简单，在admin下配置好以下几点，就可以正常使用了

 

[![image](http://blog/wp-content/uploads/2010/11/image_thumb.png)](http://blog/wp-content/uploads/2010/11/image.png)

 

首先是设置用户组（organizations)，通过组来管理用户的功能权限；接着就可以建立用户(users).设置个project名称；分类、优先级及状态一般不用设置，保持默认就好；在下面的没怎么用到；

 

bug界面比较简洁：

 

[![image](http://blog/wp-content/uploads/2010/11/image_thumb1.png)](http://blog/wp-content/uploads/2010/11/image1.png)

 

本项目是开源项目，作者写的aspx并没有采取代码分离模式，所以如果看e文不爽，直接在相应的页面中修改保存即可。

 

OVER
