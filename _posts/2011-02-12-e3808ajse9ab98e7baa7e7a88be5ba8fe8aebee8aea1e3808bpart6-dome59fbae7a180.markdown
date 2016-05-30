---
author: me115wp
comments: true
date: 2011-02-12 09:31:39+00:00
layout: post
link: http://blog.me115.com/2011/02/127
slug: '%e3%80%8ajs%e9%ab%98%e7%ba%a7%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e3%80%8bpart6-dom%e5%9f%ba%e7%a1%80'
title: 《JS高级程序设计》PART6. DOM基础
wordpress_id: 127
categories:
- 读书笔记
tags:
- JavaScript
---

**_1.使用document处理html节点：_**

 

<p id="ip1">hello </p>

 

假如oP包含指向这个元素的一个引用，则可以这样访问到id属性的值：

 

var sId = oP.attributes.getNamedItem("id").nodeValue;

 

or 更简单：var sId = op.getAttribute("id"); //对应的setAttribute("id","newId");

 

**_2.访问指定节点：_**

 

· getElementsByTagName():返回一个包含所有tagname特性等于某个指定值的集合：

 

<blockquote>  
> 
> var oImg = document.getElementByTagname("img");
> 
> </blockquote>

 

· getElementsByName():通过name来访问控件：

 

<blockquote>  
> 
> <input type="radio" name = "redColor" value="red"/>red
> 
>    
> 
> use:var or = document.getElementByName("redColor");
> 
>    
> 
> alert(var.getAttribute("or"));//"red"
> 
> </blockquote>

 

· getElementById:通过Id值来访问；这种方法效率更高，因为html中id值唯一;

 

注：如果给定的ID匹配某个元素的name特性，则IE6.0还会返回这个元素，这是IE6的一个bug！！！

 

注：DOM操作必须页面完全载入之后才能进行；在页面完全下载到客户端之前，是无法完全构建DOM树的。因此，必须使用onload时间句柄来执行所有的代码。

 

注：IE6在setAttribute()上有个很大的问题：当使用它时，变更并不会总是正确的反应出来，因此，如果要支持IE，最好尽可能使用属性(HTML DOM特征)来替换：

 

<blockquote>  
> 
> eg: var oImg = document.getElementByName("coolImg");
> 
>    
> 
> oImg.getAttribute("src") ==> oImg.src
> 
>    
> 
> oImg.setAttribut("src","mypic2.jpg"); ==> oImg.src = "mypic2.jpg";
> 
> </blockquote>

 

 

使用DOM Level2 遍历DOM(只能在Mozilla等中才有的,IE6不支持）:NodeIterator,TreeWalker

 

_**测试代码：**_

 

<blockquote>  
> 
> <html>
> 
>    
> 
> <head>
> 
>    
> 
> <script type="text/Javascript">
> 
>    
> 
> function sayHi(){
> 
>    
> 
> alert("hi");
> 
>    
> 
> window.defaultStatus = "hello,colinOrg";
> 
>    
> 
> }
> 
>    
> 
> function changeHref(){
> 
>    
> 
> var baidus = document.getElementById("baidu");
> 
>    
> 
> baidus.href="http://www.google.com/";
> 
>    
> 
> }
> 
>    
> 
> //setTimeout(function (){location.replace("http://www.ityouhui.com")},2000);
> 
>    
> 
> </script>
> 
>    
> 
> </head>
> 
>    
> 
> <body onload="changeHref()">
> 
>    
> 
> <p>1111</p>
> 
>    
> 
> <input type="button" onclick="setTimeout(sayHi,1000);"/>
> 
>    
> 
> <a href="[www.ityouhui.com](http://www.ityouhui.com)" onmouseover="window.status='ityouhui' ">ITyouhui</a>
> 
>    
> 
> <a href="[http://www.baidu.com](http://www.baidu.com)" id="baidu">这是百度链接，通过document改为Google的</a>
> 
>    
> 
> <p>222</P>
> 
>    
> 
> </body>
> 
> </blockquote>
