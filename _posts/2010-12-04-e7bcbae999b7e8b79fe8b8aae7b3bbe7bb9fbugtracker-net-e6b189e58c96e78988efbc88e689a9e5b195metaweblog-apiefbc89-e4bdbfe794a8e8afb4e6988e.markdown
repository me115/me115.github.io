---
author: me115wp
comments: true
date: 2010-12-04 11:21:02+00:00
layout: post
link: http://blog.me115.com/2010/12/65
slug: '%e7%bc%ba%e9%99%b7%e8%b7%9f%e8%b8%aa%e7%b3%bb%e7%bb%9fbugtracker-net-%e6%b1%89%e5%8c%96%e7%89%88%ef%bc%88%e6%89%a9%e5%b1%95metaweblog-api%ef%bc%89-%e4%bd%bf%e7%94%a8%e8%af%b4%e6%98%8e'
title: 缺陷跟踪系统BugTracker.NET 汉化版（扩展Metaweblog API） 使用说明
wordpress_id: 65
categories:
- WEB开发
tags:
- Bug Tracker
- 使用指南
- 汉化
- 缺陷跟踪
---

在日程项目开发的过程中，会有来自不同开发及测试人员的修改及维护需求，当项目成员较少是，通常对缺陷管理的处理方式是Email和Excel，但人员一多，就会显得混乱而无头绪。为此，我们引入了缺陷跟踪系统BugTracker。

 

以下对使用，进行简单说明：

 

首页为登录页面：

 

![clip_image002](http://blog/wp-content/uploads/2010/12/clip_image002.jpg)

 

输入密码和帐号登录；

 

 

以下是登录后的界面：

 

[![image](http://blog/wp-content/uploads/2010/12/image_thumb.png)](http://blog/wp-content/uploads/2010/12/image.png)

 

对于每个提交的缺陷（bug），都会对应一个id号。当记录非常多时，如果哦记得id号，可以直接通过顶部的跳转到ID，输入id号回车查看；

 

Flag：对于需要标记强调的缺陷记录，可自行在前面加上Flag标志（点击相应记录的方框）

 

![image](http://blog/wp-content/uploads/2010/12/image1.png)

 

描述：发布的缺陷记录的标题（缺陷记录包括标题和内容，和发Email一样）

 

当移动鼠标到描述内容上时，会弹出框显示缺陷的正文：

 

[![image](http://blog/wp-content/uploads/2010/12/image_thumb1.png)](http://blog/wp-content/uploads/2010/12/image2.png)

 

类别：提交记录的类别分为以下4种：

 

  
  * 缺陷（Bug）：这是我们使用最多的一种；对于某个问题的修改；
   
  * 完善（enhancement）：某个功能需要并不是缺陷，但由于项目的需要，需要完善功能；
   
  * 问题（question）：咨询其它开发人员问题；
   
  * 任务（task）：分配的项目开发任务；
 

![image](http://blog/wp-content/uploads/2010/12/image3.png)

 

提交者：缺陷的提交人员；

 

优先级：分为3等：高，中，低；按提交内容的情况来定，如无特殊需要，一般提交问题时请选择 “低”；

 

处理着：提交的缺陷需要由谁来处理；

 

状态：缺陷处理的状态分为5等：

 

![image](http://blog/wp-content/uploads/2010/12/image4.png)

 

  
  * 新提交：缺陷提交者初始发布的缺陷；
   
  * 进展中：处理者已经看到这条记录，正在修改，但问题比较复杂，需要一段时间，处理者可以将缺陷状态改为“进展中”；
   
  * 已修改：缺陷修改完成后，处理者将缺陷状态置为 “已修改”，随后，由测试人员进行测试；
   
  * 重新开放：测试人员测试 已修改的 缺陷，发现还有问题，则将该记录置为“重新开放”，由处理者 继续修改（完成之后，仍置为“已修改”）；
   
  * 关闭：测试人员测试 “已修改”的缺陷，没有问题后，可将该记录置为“关闭”；
 

注：缺陷的处理人员 修改完缺陷后不能将 记录置为 “关闭”；

 

 

 

左上方是缺陷记录筛选器：

 

![image](http://blog/wp-content/uploads/2010/12/image5.png)

 

  
  * Bug开放修改：缺陷状态不是“关闭”的所有记录都被选择；
   
  * 包含附件：缺陷记录中包含附件的；
   
  * 所有BUG：所有记录；
   
  * 提交时长-状态：查看各项记录提交后经过的时间，及目前处理的状态；
   
  * 需要我处理： 选择 处理者为登录用户的记录；
   
  * 已修改-待测试：选择记录状态为“已修改”的记录；
 

 

打印BUG列表，和打印Bug详情 则是将当前显示记录 打印成一张HTML网页（这个功能对于访问服务器网速慢的开发人员相当有用，可以选择需要处理的记录 打印下来，都处理完成之后上web提交）；

 

 

添加缺陷：

 

以下是添加缺陷的界面：

 

![image](http://blog/wp-content/uploads/2010/12/image6.png)

 

提交一个缺陷时，需要写描述和正文，选择缺陷类别、处理的优先级，处理者，以及状态；

 

注：右上方的use/save非常有用，当选择了类别，优先级、处理者及状态后，使用save将目前选择可以保存下来，下次需使用相同选择时，只需点击user即可；

 

 

 

 

 

 

 

对于缺陷添加，如果单纯只是文本，则通过web方便的添加，如果包含图片，这样的界面就不是很合适了，为此，提供以下方法：

 

1.使用第三方插件（bugShooting),截图后直接发送到该界面进行缺陷添加；

 

2.为了更方便的使用，我为这个BugTracker开发了一个Metaweblog API接口，大家可以使用LiveWriter提交缺陷（如果提交的记录中包含多张图，推荐使用这种方式）；

 

3.后续将支持使用Email提交缺陷记录（目前尚不支持）；

 

 

对于问题的处理，点击相应的描述标题进入后，与缺陷添加界面一样，很方便操作。

 

OVER
