---
author: me115wp
comments: true
date: 2011-07-04 07:48:41+00:00
layout: post
link: http://blog.me115.com/2011/07/159
slug: datascraper%e6%95%b0%e6%8d%ae%e6%8a%93%e5%8f%96%e6%ad%a5%e9%aa%a4-%e5%85%a8
title: DataScraper数据抓取步骤-全
wordpress_id: 159
categories:
- WEB开发
tags:
- DataScraper
---

**_一.定义数据采集规则：_**

 

###### 1.命名采集主题

 

运行MetaStudio后，如图1执行下面的步骤。

 

![](http://www.gooseeker.com/files/images/theme.preview.jpg)

 

###### 2.定义采集规则

 

创建整理箱：

 

首先建立一个顶部整理箱：shop；在弹出的框中输入信息属性名称、点击key复选框（这个顶部整理箱不需要进行Freefomat映射）

 

在这个整理箱上右击，点击-》添加包容；新建整理箱；

 

在新建的整理箱上通过 右击-》其后添加其余的整理箱；

 

注意：对于需要多级抓取的，需要增加一个page整理箱，勾选key、clue、url；

 

内容映射：

 

定位到第一条，定位至需要抓取的文本行，选择内容映射；

 

对于page同样需要内容映射，不过选择的是href：

 

![image](http://blog/wp-content/uploads/2011/07/image.png)

 

FreeFomat映射：

 

将对第一块设置的映射模式应用到整个页面，针对各个快使用到的最相近的class，进行FreeFormat映射；

 

规则定义完成；

 

更多细节参考：

 

#### 采集当当百货价格以建立比价系统

 

[http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/defineschema.html](http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/defineschema.html)

 

 

 

**_二、翻页抓取_**

 

对于列表页，翻页抓取：

 

###### 1.1 创建线索

 

![](http://www.gooseeker.com/files/images/clue_create.preview.png)

 

  
  1. 转到Clue Editor工作台 
   
  2. 点击**newClue**按钮，创建一个线索 
   
  3. 点击**Marker**类型，设定为记号线索；
 

![](http://www.gooseeker.com/files/images/clue_map.preview.png)

 

 

 

4.设置记号映射 “下一页”字样就是记号 

 

![](http://www.gooseeker.com/files/images/mark_map.preview.png)

 

对于Clue1（page的clue）

 

将目标主题设置为popshopdet；这样就和下一级跳转的建立了联系；

 

翻页抓取完毕，更多参考：

 

#### 《翻页抓取当当网价格数据》

 

[http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/bulkscrape.html](http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/bulkscrape.html)

 

 

**_三。多级抓取_**

 

参考：

 

#### DataScraper数据抓取快速入门

 

[http://blog.donews.com/me1105/archive/2011/04/09/144.aspx](http://blog.donews.com/me1105/archive/2011/04/09/144.aspx)

 

#### 卓越网商品数据分级抓取

 

[http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/multilayers.html](http://www.gooseeker.com/cn/node/document/metaseeker/cookbookv4/multilayers.html)

 

 

 

 
