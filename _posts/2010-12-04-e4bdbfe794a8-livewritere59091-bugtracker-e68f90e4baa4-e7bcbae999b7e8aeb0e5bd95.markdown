---
author: me115wp
comments: true
date: 2010-12-04 14:02:34+00:00
layout: post
link: http://blog.me115.com/2010/12/80
slug: '%e4%bd%bf%e7%94%a8-livewriter%e5%90%91-bugtracker-%e6%8f%90%e4%ba%a4-%e7%bc%ba%e9%99%b7%e8%ae%b0%e5%bd%95'
title: 使用 LiveWriter向 BugTracker 提交 缺陷记录
wordpress_id: 80
categories:
- WEB开发
tags:
- Bug Tracker
- 缺陷跟踪
- 集成MeataWeblog API
---

通过LiveWriter可以很方便写出图文结合的缺陷记录，能清晰表达提交者传达的信息。以下说明配置方法：

1.下载安装LiveWriter：[http://explore.live.com/windows-live-writer-xp](http://explore.live.com/windows-live-writer-xp) 

.配置帐号：

![image](http://blog/wp-content/uploads/2010/12/image7.png) 

2.选择工具-> 帐户：

点击“添加”按钮：

[![image](http://blog/wp-content/uploads/2010/12/image_thumb2.png)](http://blog/wp-content/uploads/2010/12/image8.png)

3.选择日志服务，这里选择“其它日志服务”

[![clip_image002](http://blog/wp-content/uploads/2010/12/clip_image002_thumb.jpg)](http://blog/wp-content/uploads/2010/12/clip_image0021.jpg)

4.添加日志账户：

日志网址输入：[http://www.***.net:8082/bt/MetaWeblogAPI.ashx](http://me115.gicp.net:8082/bt/MetaWeblogAPI.ashx)

用户名和密码 是 BugTracker的用户名和密码；

[![clip_image002[7]](http://blog/wp-content/uploads/2010/12/clip_image0027_thumb.jpg)](http://blog/wp-content/uploads/2010/12/clip_image0027.jpg)

5.检索之后会要求选择日志类型：

日志类型选择： MetaWeblog API:

远程发布URL：[http://www.***.net:8082/bt/MetaWeblogAPI.ashx](http://me115.gicp.net:8082/bt/MetaWeblogAPI.ashx)

[![clip_image002[9]](http://blog/wp-content/uploads/2010/12/clip_image0029_thumb.jpg)](http://blog/wp-content/uploads/2010/12/clip_image0029.jpg)

6.下一步，问是否发布一篇测试日志，选择否，即可。配置完成。

完成之后，在帐号配置表中可见

[![image](http://blog/wp-content/uploads/2010/12/image_thumb3.png)](http://blog/wp-content/uploads/2010/12/image9.png)

7.由于提交的缺陷记录最重要的附加属性是处理者，所以在这里，我将提交记录的类别转化为了处理者的选择。

在右下方设置类别处，点击刷新，获取项目开发的人员列表，选择需要处理该缺陷的人员（多选无用，只支持选择一个）：

[![image](http://blog/wp-content/uploads/2010/12/image_thumb4.png)](http://blog/wp-content/uploads/2010/12/image10.png)

8.LiveWriter对于内容的编辑相当方便了，可以使用截图工具截图后直接粘贴。

9.点发布即可。

注：再没有关闭LiveWriter的情况下，再次发布缺陷记录需要点击 “新建”，否则，就是回复上次发布的缺陷记录；
