---
author: me115wp
comments: true
date: 2013-01-05 05:48:14+00:00
layout: post
link: http://blog.me115.com/2013/01/224
slug: c%e5%ad%a6%e4%b9%a0-%e5%ba%94%e7%94%a8%e7%af%87%ef%bc%88windowslinux%ef%bc%88%e4%b9%a6%e7%b1%8d%e6%8e%a8%e8%8d%90%e5%8f%8a%e5%88%86%e4%ba%ab%ef%bc%89
title: C++学习--应用篇（Windows/Linux)（书籍推荐及分享）
wordpress_id: 224
categories:
- C++编程
- CC书评
---

我将以平台划分，分别介绍Windows和Linux下个人认为的好书（与基础篇一样，大部分都提供有电子版）；

对于C++基础类的图书，这里不再重复，有兴趣的朋友请移步《[C++学习–基础篇](http://blog.me115.com/2012/12/214)》。


## **Windows篇**


在Windows平台下主要说的是VC编程（使用MFC），以下将对VC开发的相关图书介绍；对于ATL等，涉及不多，不做评论；

《[Windows程序设计](http://www.amazon.cn/Windows%E7%A8%8B%E5%BA%8F%E8%AE%BE%E8%AE%A1-%E4%BD%A9%E6%8E%AA%E5%B0%94%E5%BE%B7/dp/B00426BTC6?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B00426BTC6)》

这是一本绝版的图书，算是Windows平台下编程指南图书的鼻祖。网络传言甚好，由于年代久远，一般不好下载了，在这里给出下载地址。方便朋友；

本书每章的例子都是使用的Windows下的原始API开发的，一个基本窗口的显示也需要自己动手编写接受消息的循环以及消息翻译的逻辑；即使是使用MFC开发的朋友，这本书的作用也很明显，它能让你自行解剖MFC框架程序，明明白白的看透MFC是如何为我们做底层封装，如何实现消息链传递（结合《[深入浅出MFC](http://www.me115.com/book/126.html)》，整个流程就相当透彻了）；

《[Visual C++技术内幕](http://www.amazon.cn/Visual-C-%E6%8A%80%E6%9C%AF%E5%86%85%E5%B9%95-%E5%B0%8F%E5%85%8B%E9%B2%81%E6%A0%BC%E6%9E%97%E6%96%AF%E5%9F%BA-%E6%BD%98%E7%88%B1%E6%B0%91%E7%AD%89/dp/B00AFXF0GQ?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B00AFXF0GQ)》

本书名称上看似高深，但并不非内幕系图书；可以做为项目开发的一本工具书，实践应用。全但不深，是VC方面的基础书籍；内容中对于VS操作的插图讲解很多，浅显易懂；

《[深入浅出 MFC 第二版](http://www.me115.com/book/126.html)》

本书是MFC的经典读本，相信搞VC开发的很多人都看过，接着又忘了。是否该再来一遍，自测一下： 1.MFC中的生死循环（就是整个从程序开始运行到结束，主要经过的函数流程） 2.MFC中使用消息映射机制来分发消息，考虑与使用虚函数相比 有何优缺点？ 3.MFC中用到的模式有哪些？

虽然微软的VC.net已经推出有好些年了，但纯VC的开发程序仍然在国内流行。比如建筑、工程测量方面的软件大部分还是VC开发（工具甚至还有VC6.0）。这本书是97年出版的，直到今天，对于需要掌握MFC的朋友来说，它仍然是不二之选；

《[Windows核心编程(第5版)》](http://www.amazon.cn/Windows%E6%A0%B8%E5%BF%83%E7%BC%96%E7%A8%8B-%E6%9D%B0%E5%A4%AB%E7%91%9E/dp/B001GS7918?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B001GS7918)

对于一般GUI程序的开发，看看VC技术内幕就可以动手；但涉及到进程间通信，涉及到多线程、互斥、信号量，不看这本书，就太可惜了。讲解全面而深入，Windows系统的各种核心对象都涉及到了。强烈推荐；

两句话：

Windows平台下的经典；

Windows开发必备；

[VC知识库](http://www.vckbase.com/)：

这是我不得不提的一个网站。VC知识库制作了一个源代码ISO光盘和在线杂志（提供chm包离线下载），分门别类的将各种程序、各种技术文章分类，制作到一个chm文件中，对VC的朋友来说，有很大的参考价值。在我开发Windows程序的几年中，我会经常查阅VC知识库的代码仓库和在线杂志（有源码）；

比如，对于数据库访问，如果之前从来都没做过，都想有几篇手把手的文章，要是能再来个实例，那就最好。类似这样的需求，vc知识库的在线杂志可以完美满足；




## **Linux/Unix篇**


Linux服务器端的魅力对于同时把玩过Windows和linux服务器的人来说，体会会非常深刻；相比与Windows，其管理操作方便很多，也强大很多。Windows平台下，DOS、批处理，功能还很弱，多数时刻还是得通过远程桌面来管理各种程序;

Linux则不一样，所有的程序都是在黑屏下，操作顺滑流畅，散发五指快弹魅力；

《[鸟哥的Linux私房菜 基础学习篇(第二版)](http://www.amazon.cn/%E9%B8%9F%E5%93%A5%E7%9A%84Linux%E7%A7%81%E6%88%BF%E8%8F%9C%E5%9F%BA%E7%A1%80%E5%AD%A6%E4%B9%A0%E7%AF%87-%E9%B8%9F%E5%93%A5/dp/B0011FB674?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B0011FB674)》

对于从未接触过Linux提供的同学来说，本书算是一个福音，以轻松诙谐的语言说清了常用的系统命令用法及语境； 对Linux零基础的同学，推荐阅读；

我看过的比较全面的讲解Linux基础的书是《[LPI LINUX认证权威指南](http://www.amazon.cn/LPI-Linux%E8%AE%A4%E8%AF%81%E6%9D%83%E5%A8%81%E6%8C%87%E5%8D%97-Adam-Haeder/dp/B00C8QMLXI?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B00C8QMLXI)》，写的比较硬，但知识点扎实，作为需要全面梳理一遍linux基础知识的同学，还是可以好好看看，当然了，比较枯燥，如果不是有考试的动力，一般也看不动。

《[Linux程序设计](http://www.me115.com/book/77.html)》

写的中规中矩，linux下编程入门指导书，讲解全面，但不深入；

这本书能告诉你在linux上如何编译一个程序，如何使用gdb，但是，它不会告诉你信号量如何使用；

看完必然不解渴，当然得来一剂《[unix环境高级编程](http://www.amazon.cn/UNIX%E7%8E%AF%E5%A2%83%E9%AB%98%E7%BA%A7%E7%BC%96%E7%A8%8B-%E5%8F%B2%E8%92%82%E6%96%87%E6%96%AF/dp/B00114GRG0?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B00114GRG0)》。

《[UNIX编程艺术](http://www.amazon.cn/%E4%BC%A0%E4%B8%96%E7%BB%8F%E5%85%B8%E4%B9%A6%E4%B8%9B-UNIX%E7%BC%96%E7%A8%8B%E8%89%BA%E6%9C%AF-%E5%9F%83%E7%91%9E%E5%85%8B%E2%80%A2S-%E7%90%86%E6%9B%BC%E5%BE%B7/dp/B008Z1IEQ8?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B008Z1IEQ8)》

个人认为最精彩的是前3章，将Unix的历史和渊源完美展现在读者眼前； 本书从头到尾就是在宣传Unix的文化，阐明了我们常用工具的历史及设计思想； 当你在vi和emacs的选择上有困惑时，在python和perl间的选择困惑时，可以参考本书，你会得到一个中肯的建议。

《[unix环境高级编程](http://www.amazon.cn/UNIX%E7%8E%AF%E5%A2%83%E9%AB%98%E7%BA%A7%E7%BC%96%E7%A8%8B-%E5%8F%B2%E8%92%82%E6%96%87%E6%96%AF/dp/B00114GRG0?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B00114GRG0)》

对于巨著，我一向神往，国外的作者一般都能把理论和概念讲得透彻而生动； 对于本书，我的观点是：想说爱你不容易；确为经典好书，但那密密麻麻的排版格式，那一行行艰深的文字，让我一次次裹足不前。 每当有技术难点，都能在Stevens系列中找到答案。是该静下心来，好好读完它，然后常驻案边；

《[UNIX网络编程 卷2](http://www.amazon.cn/UNIX%E7%BD%91%E7%BB%9C%E7%BC%96%E7%A8%8B-%E5%8D%B72-%E8%BF%9B%E7%A8%8B%E9%97%B4%E9%80%9A%E4%BF%A1-W-Richard-Stevens/dp/B003URY2XQ?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B003URY2XQ)》

该书的另一名称：Unix系统IPC编程圣经 在工作中，需要用到进程间通信，以及想掌握IPC知识的，本书不可或缺；对于共享内存的讲解方面，也非常透彻；
