---
author: me115wp
comments: true
date: 2011-02-10 12:55:39+00:00
layout: post
link: http://blog.me115.com/2011/02/118
slug: '%e3%80%8ajs%e9%ab%98%e7%ba%a7%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e3%80%8bpart3-%e5%af%b9%e8%b1%a1%e5%9f%ba%e7%a1%80'
title: 《JS高级程序设计》PART3.对象基础
wordpress_id: 118
categories:
- 读书笔记
tags:
- JavaScript
---

_**3.2****对象应用******_

 

· 对象废除：如果一个对象有2个及以上引用，则要正确的废除该对象，必须将其所有引用都设置为null。

 

js和java一样，有垃圾回收机制，会自动收回已无引用指向的对象。

 

· 早绑定和晚绑定

 

绑定：把对象的接口和实例结合在一起的方法。

 

早绑定：指在实例化对象之前定义他的特性和方法；

 

玩绑定：指在编译器或解释程序在运行前，不知道对象的类型。ECMASCript采用的是晚绑定。

 

_**3.3****对象类型******_

 

**1.本地对象**

 

· Array类

 

以下用示例来说明其用法：

 

<blockquote>  
> 
> var arry = new Array(10); //不知大小时，可以省略，在后面的代码中可以任意增加
> 
>    
> 
> var arry = new Array("red","green","blue");
> 
>    
> 
> alert(arry[1]);//"green"
> 
>    
> 
> var sCor = "red,green,blue";
> 
>    
> 
> var arry = sCor.split(",");//转化为Array对象
> 
>    
> 
> var sCor = "green";
> 
>    
> 
> var arry = sCor.split("");//使用空串分割，则会分成各个字符："g,r,e,e,n"
> 
> </blockquote>

 

Array有2个String具有的方法：concat()：连接 /slice():截取部分内容

 

Array提供了栈的功能：

 

<blockquote>  
> 
> var stack = new Array;
> 
>    
> 
> stack.push("red");
> 
>    
> 
> stack.push("green");
> 
>    
> 
> alert(stack.toString());//"red,green"
> 
>    
> 
> var aa = stack.pop();//"green"
> 
> </blockquote>

 

shift():将删除数组第一项，并作为函数值返回。/Unshift反之。

 

通过shift和push()，可完成队列的功能。

 

· Date类

 

<blockquote>  
> 
> var d = new Date(Date.parse("6/1/2011"));//如果传递的字符串无法转为日期，将为NaN
> 
>    
> 
> var d = new Date(Date.UTC(2011,0,6));//设置月份特别注意，因为其月的设置从0开始 （2011-1-6）
> 
> </blockquote>

 

**2.内置对象**

 

ECMASCript中提供了2个内置对象：Global和Math；

 

Global的eval（）方法：该方法就像整个ECMASCript解释程序，接受一个参数，将其解释为真正的ECMASCript语句，然后把它插入到该函数所在的位置。

 

**3.宿主对象**

 

所有非本地对象都是宿主对象，即由ECMASCript实现宿主环境的对象。所有的BOM和DOM对象都是宿主对象。

 

_**3.5****定义类或对象******_

 

使用构造函数形式：

 

<blockquote>  
> 
> function Car (sColor,iDoor){
> 
>    
> 
> this.color = sColor;
> 
>    
> 
> this.doors = iDoor;
> 
>    
> 
> this.showColor = function(){
> 
>    
> 
> alert(this.color)
> 
>    
> 
> };
> 
>    
> 
> }
> 
>    
> 
> var oCar = new Car ('red',4);
> 
>    
> 
> var oCar2 = new Car("green",3);
> 
> </blockquote>

 

注：对象的结构不用实现定义，直接在构造函数中按需取用。

 

_以上创建对象中所拥有的函数为__2__份，出现内存浪费。（__c++__、__java__中所有对象的函数共用一份）___

 

改进：将函数提出来,在构造函数之后，用prototype添加.(prototype属性可用来定义方法）

 

Car.prototype.showColor = function(){ alert(this.color};

 

_**3.6****修改对象******_

 

创建新方法：Number.prototype.toHexString = function(){return this.toString(16)};

 

重定义已有的方法：再定义一遍，就会覆盖，因为ECMASCript没有重载。
