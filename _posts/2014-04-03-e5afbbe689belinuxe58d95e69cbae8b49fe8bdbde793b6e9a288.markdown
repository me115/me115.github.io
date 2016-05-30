---
author: me115wp
comments: true
date: 2014-04-03 12:15:53+00:00
layout: post
link: http://blog.me115.com/2014/04/522
slug: '%e5%af%bb%e6%89%belinux%e5%8d%95%e6%9c%ba%e8%b4%9f%e8%bd%bd%e7%93%b6%e9%a2%88'
title: 寻找Linux单机负载瓶颈
wordpress_id: 522
categories:
- Linux&amp;Unix
tags:
- linux linux工具
- 性能优化
---

寻找Linux单机负载瓶颈





![image](http://blog.me115.com/wp-content/uploads/2014/04/image1.png)





服务器性能上不去，是哪里出了问题?IO还是CPU？只有找到瓶颈点，才能对症下药；     
如何寻找Linux单机负载瓶颈，遵循的原则是不要推测，我们要通过测量的数据说话；





负载分两类：     
1.CPU负载；      
2.IO负载；





## 排查流程





1.查看平均负载（top/uptime命令）     
2.确认CPU、IO有无瓶颈；（使用 sar vmstat）      
3.CPU负载过高时寻找流程：      
4.IO负载过高时寻找流程；





## 查看平均负载





先通过top命令查看服务器是否出现负载过重的状况，之后，再具体使用工具来分析出是CPU负载过高还是IO负载过高；     
比如，使用sar工具查看CPU使用率和IO等待率（sar的具体使用教程参考这篇文章：      
[](http://blog.me115.com/2013/12/468)[http://blog.me115.com/2013/12/468](http://blog.me115.com/2013/12/468)      
top的结果：      
load average：0.7, 0.66,0.59      
平均负载分别表明从左到右1分钟、5分钟、15分钟内，单位时间内处于等待状态的任务数；      
（等待 的意思 表明在等待cpu、或者等待IO）





## CPU负载过高时的寻找流程





使用top、sar确认目标程序；     
再通过ps查看进程状态和CPU使用时间等；      
进一步寻找：通过strace 或 oprofile命令；





## IO负载过高的寻找流程





IO负载过高，多半是程序发出的IO请求过多导致负载过高，或是发生页面交互导致频繁访问磁盘；     
应通过sar或vmstat确认交换区状态，以找出原因；      
如果是发生页面交互的情况，通过以下步骤调查：      
1.使用ps工具确认是否有进程消耗了大量内存；      
2.如果由于程序故障造成内存消息过大，应改进程序；      
3.内存不足则增加内存；





如果没有交换发生，而且磁盘IO频繁，可能是用于缓存的内存不足；     
1.考虑扩大缓存，增加内存；      
2.考虑分散存储





Posted by: 大CC | 04APR,2014     
博客：[blog.me115.com](http://blog.me115.com)      
微博：[新浪微博](http://weibo.com/bigcc115)



