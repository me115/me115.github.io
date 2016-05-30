---
author: me115wp
comments: true
date: 2011-02-12 09:25:21+00:00
layout: post
link: http://blog.me115.com/2011/02/122
slug: '%e3%80%8ajs%e9%ab%98%e7%ba%a7%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e3%80%8bpart5-%e6%b5%8f%e8%a7%88%e5%99%a8%e4%b8%ad%e7%9a%84javascript'
title: 《JS高级程序设计》PART5.浏览器中的JavaScript
wordpress_id: 122
categories:
- 读书笔记
tags:
- JavaScript
---

随着XHTML（可扩展html）标准的出现，<script>标签不在用language特性，而用type特性声明内嵌代码的mime类型，JavaScript的mime类型是"text/JavaScript"，如：

 

<script type="text/javascript" > </script>

 

即使许多浏览器不完全支持XHTML，是现在都用type特性，而不用language特性，以提供更好的XHMTL扩展。注：省略language特性不会带来任何问题。

 

XHTML的第二个改变是使用CDATA段，XML中的CDATA段用于声明不应被解析为标签的文本，这样，就可以使用特殊字符，如 <,>,&" 等；但大多数浏览器不完全支持XHTML，所以我们可采用如下兼容性写法：

 

<script type="text/javascript">

 

//<![CDATA[

 

function ...

 

//]]>

 

</script>

 

_** BOM****（浏览器对象模型）******_

 

BOM体系结构图：

 

[[![clip_image002](http://blog/wp-content/uploads/2011/02/clip_image002_thumb1.jpg)](http://blog/wp-content/uploads/2011/02/clip_image0023.jpg)](http://blog/wp-content/uploads/2011/02/clip_image0022.jpg)

 

window对象是整个BOM的核心，所有对象都以某种形式回接到window对象。

 

**1.window****对象******

 

  
  * window对象标识整个浏览器窗口，但不标识其中包含的内容。window对象可用于移动或调整他表示的浏览器的大小或其它影响。
 

如果页面是框架结合，每个框架都由它自己的window对象表示，存放在frame集合中。使用window.frames[1]来访问。（如frame定以了名称，也可使用名来访问：window.frames["leftframe"];)

 

由于window对象是整个BOM的中心，其有种特权，即不需要明确引用它。所有以上代码可写为:frames["leftframe"];

 

window的另一个实例是parent。

 

  
  * 系统对话框：alert/confirm/prompt:prompt用于获取用户输入的文本；
   
  * 状态栏：window的2个属性status和defaultstatus可以改变状态栏文本；使用如下代码可在地址栏中隐藏链接：
 

<a href="[http://www.ityouhui.com](http://www.ityouhui.com)" onmouseover="window.status='ityouhui' ">ITyouhui</a>

 

  
  * window.setTimeout()方法用于设置暂停：
 

function sayHi(){alert("hi");}

 

setTimeout(sayHi,1000);

 

如果要执行一组指定的代码前需要等待一段时间，使用setTimeout。如果要反复执行某些代码，则可以使用setInterval（）方法（参数与setTimeout相同）

 

访问浏览器窗口的历史：

 

  
  * window.history.go(-1):向后导航一页;//back()方法达到同样效果
 

**2.document****对象******

 

document.title：<title>标签中显示的文本

 

**3.location****对象******

 

  
  * BOM中最有用的对象之一，它是window对象和document对象的属性。 window.location和document.location等价，可以交换使用。
   
  * location.href是最常用的属性，用于获取或设置窗口的url；
   
  * 如果不想让包含脚本的页面能从浏览器历史中访问，可使用replace（）方法：location.replace([http://www.ityouhui.com](http://www.ityouhui.com))；这样，跳转到新的页面后，就无法再通过浏览器的导航来访问之前的页；
   
  * location.reload:当前页面重新载入；有两种模式：reload(false):从缓存中载入；reload(true):从服务器载如；省略的默认参数为false；
 

**4.navigator****对象******

 

  
  * navigator对象包含大量有关web浏览器的信息。
   
  * 在判断浏览器页面采用的是哪种浏览器时，navigator对象非常有用。
 

**5.screen****对象******

 

获取用户屏幕信息；

 
