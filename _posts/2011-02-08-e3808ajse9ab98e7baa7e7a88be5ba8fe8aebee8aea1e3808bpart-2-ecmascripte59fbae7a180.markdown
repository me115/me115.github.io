---
author: me115wp
comments: true
date: 2011-02-08 15:22:49+00:00
layout: post
link: http://blog.me115.com/2011/02/110
slug: '%e3%80%8ajs%e9%ab%98%e7%ba%a7%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e3%80%8bpart-2-ecmascript%e5%9f%ba%e7%a1%80'
title: 《JS高级程序设计》PART 2.ECMASCript基础
wordpress_id: 110
categories:
- 读书笔记
tags:
- JavaScript
---

**_2.1 语法_**

 

ECMASCript的基础概念如下：

 

· 区分大小写。

 

· 变量是弱类型的。与java和c不同，ECMASCript中的变量无特定的类型，定义变量时只用var运算符，可以将它初始化为任意的值。这样可以随时改变变量所存数据的类型（尽管应尽量避免这样做）。

 

· 每行结尾的分号可有可无。如果没有分号，ECMASCript就把这行代码的结尾看作该语句的结尾，前提是这样没有破坏代码的语义。（注：最好的代码编写习惯是总加入分号，因为没有分号，有些浏览器就不能正确运行）。

 

· 注释与java、c、php语言相同。单行注释：// 多行注释： /* ^^^ */

 

· 括号表明代码块。

 

**_2.2变量_**

 

· var test = "hello";//由于ECMASCript是弱类型，解释程序会为test自动创建一个字符串值，无需明确的类型申明。

 

· var test="hi",age = 25;//使用同一var语句定义的变量不必具有相同的类型。

 

· var test;//ECMASCript变量并不一定要求初始化（它们是在幕后完成初始化）

 

· 在使用变量之前不必一定有声明。ECMASCript的解释程序在遇到未声明的标识符时，用该变量创建一个全局变量，并将其初始化为指定的值。如果不能紧密跟踪变量，这样做也很危险。（注：最好习惯是像其它语言那样，使用前总是声明所有变量）。

 

eg： stest = stest + "hi";

 

变量命名需要遵守的规则：

 

· 第一个字符必须是字母、下划线（_）或美元符号（$）；

 

· 余下的字符可以是下划线、美元符号或任何字母或数字字符。

 

· 变量命名应遵守以下某条著名的命名规则：

 

· Camel标记法：首字母小写、接下类的单次都以大写字母开头。eg：var myTestValue;

 

· Pascal标记法：首字母大写，接下来的单次都已大写字母开头。eg：var MyTestValue;

 

· 匈牙利类型标记法：在以Pascal标记法命名的变量前附加一个小写字母，说明该变量的类型。eg：var iMyTestValue = 0 , sMyStr = "hi"; （注：推荐使用这种方法，更易阅读）

 

下面列出匈牙利类型标记法定义ECMASCript变量使用的前缀：    <table cellpadding="0" border="1" cellspacing="0" ><tbody >       <tr >         
<td width="208" valign="top" >           

类型

        
</td>          
<td width="86" valign="top" >           

前缀

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

数组

        
</td>          
<td width="86" valign="top" >           

a

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

布尔型

        
</td>          
<td width="86" valign="top" >           

b

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

浮点型

        
</td>          
<td width="86" valign="top" >           

f

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

函数

        
</td>          
<td width="86" valign="top" >           

fn

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

整数（数字）

        
</td>          
<td width="86" valign="top" >           

I

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

对象

        
</td>          
<td width="86" valign="top" >           

o

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

正则表达式

        
</td>          
<td width="86" valign="top" >           

re

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

字符串

        
</td>          
<td width="86" valign="top" >           

s

        
</td>       </tr>        <tr >         
<td width="208" valign="top" >           

变型（可以是任何类型）

        
</td>          
<td width="86" valign="top" >           

v

        
</td>       </tr>     </tbody></table>

 

**_2.5原始值和引用值_**

 

在ECMASCript中，变量可以存放2中类型的值，既原始值和引用值。

 

· 原始值：是存储在栈中的简单数据段，也就是说，她们的值直接存在变量访问的位置。

 

· 引用值：是存储在堆中的对象，也就是说，存储在变量出的值是一个指针，指向存储对象的内存处。

 

为变量赋值时，ECMASCript解释程序需要判断该值是原始类型还是引用类型。原始类型的特征：Undefined、Null，Boolean、数字和string型。由于这些原始类型占据的空间是固定的，所以可以将其存储在较小的内存区域-栈中。这样存储便于迅速查询变量的值。

 

**_2.6原始类型_**

 

· Undefined类型：

 

<blockquote>  
> 
> 变量未定义，未初始化，以及函数返回无明确的返回值时，都是Undefined。
> 
>    
> 
> var tmp;
> 
>    
> 
> alert(tmp == Undefined）;//true
> 
> </blockquote>

 

· Null类型：值Undefined实际上是从null派生而来，因此ECMASCript把它们定义为相等的：

 

<blockquote>  
> 
> alert( null == Undefined);
> 
> </blockquote>

 

· Boolean类型：有2个值，既true和false；

 

· Number类型：既可以表示32位整数，又可以表示64位浮点数。

 

<blockquote>  
> 
> 几个特殊的数字类型的值：
> 
>    
> 
> Number.MAX_VALUE Number.MIN_VALUE
> 
>    
> 
> NaN:表示非数字。NaN一般发生在类型转换失败时。它与自身不相等：
> 
>    
> 
> alert(NaN == NaN);// false
> 
>    
> 
> 注：不推荐使用NaN值本身，而使用函数isNaN（）：
> 
>    
> 
> alert(isNaN("blue"));// true
> 
>    
> 
> alert(isNaN("3"));//false
> 
> </blockquote>

 

· String类型

 

<blockquote>  
> 
> 字符串字面常量是由单引号或双引号声明的。ECMASCript中没有字符类型，只有字符串类型，所有单双引号皆可。
> 
> </blockquote>

 

**_2.7转换_**

 

1.转换成字符串

 

· ECMASCript的Boolean、数字和字符串的原始值都是伪对象，都有属性和方法。eg：

 

<blockquote>  
> 
> var sColor = "hello";
> 
>    
> 
> alert(sColor.length);//ouput "5"
> 
> </blockquote>

 

· 3种主要的原始值Boolean、数字和字符串都有toString（）方法，用于转换为字符串。

 

2.转换为数字

 

parseInt() 和parseFloat()：只有对String类型调用这些方法，才能正确运行，其它类型返回的都是NaN；

 

3.强制类型转换

 

ECMASCript可用的3中强制类型转换：

 

  
  * Boolean(value):把给定值转换为Boolean型；
   
  * Number(value):将给定值转换为数字（可以是整数或浮点）；注：其处理方法与parseInt及pasrseFloat相似，但是他转换的是整个值，而不是部分值。4.5.6使用parseInt转换后为4.5，而使用Number转换的为NaN。
   
  * String(value):转换为字符串，可将null也转换为字符串"null"
 

**_2.8引用类型（类）_**

 

对象是由new加上要实例化的类的名字创建：var o = new object();//括号非必须，但最好写上；

 

Object.ValueOf()：对于许多类，返回最适合该对象的原始值。

 

eg: var oNum = new Number(99);

 

oNum.toFixed(2);// 99.00

 

 

String类：

 

length()：返回其长度

 

charAt(pos):返回pos处的字符；

 

charCodeAt(pos):返回pos处字符的asc码；与此类似：indexOf()/lastIndexOf()

 

localeCompare():字符串比较；

 

从字串创建字符串：slice()/substring()：与concat()方法一样，这2个方法都不改变String对象自身的值，她们只返回原始的String值，保持String对象不变。

 

<blockquote>  
> 
> 对于负数参数，slice方法会用字符串的长度加上参数，substring则将其作为0处理。（正数2者相同）。
> 
>    
> 
> eg：var oStr = new String("hello world");
> 
>    
> 
> oStr.slice(3,-4);//"lo w"
> 
>    
> 
> oStr.substring(3,-4);// "hel" //substring总是把较小的数字作为起始位，substring(3,0)->substring(0,3);
> 
> </blockquote>

 

大小写转换：toLowerCase()/toLocaleLowerCase()/toUpperCase()/toLocaleUpperCase()

 

**注：****String****类的所有属性和方法都可以直接用于****String****原始值上，因为它们是伪对象。**

 

****

 

_**2.9运算符**_

 

void：void运算符对任何值都返回Undefined。该运算符通常用于避免输出不应该输出的值，例如，从Html的<a>元素调用js函数时。

 

<a href="javascript:void(window.open('about:blank'))">click me </a>

 

**_2.10语句_**

 

ECMASCript和java中的switch语句有2点不同。在ECMASCript中switch语句可以用于字符串，而不能用于不是常量的值说明情况：

 

var BLUE="blue",RED= "red";

 

switch(sCol){

 

case BLUE:…;

 

case RED:…;

 

default:…;

 

}

 

**_2.11函数_**

 

函数是ECMASCript的核心。函数是由关键字function、函数名加一组参数以及置于括号中要执行的代码声明的。

 

即使函数有返回值，也不必明确声明。只需要使用return运算符后跟要返回的值即可。

 

function sum(iNum1,iNum2){

 

return iNum1 + iNum2;

 

}

 

 

无重载

 

ECMASCript中的函数不能重载。可用相同的名字在同一个作用域中定义2个函数，而不会引发错误，但真正执行的是后一个函数。

 

 

arguments对象

 

在函数代码中，使用特殊对象arguments，开发者无需指出参数名，就能访问它们。

 

eg：function howManyArgs(){

 

alert(arguments.length);

 

alert(arguments[0]);

 

}

 

howManyArgs("string",45);//2,"string"

 

howManyArgs();//0 Undefined

 

**注：与其它程序设计语言不同，****ECMASCript****不会验证传递给函数的参数个数是否等于函数定义的参数个数。开发者定义的函数都可以接受任意格式的参数（最多****25****个），而不会引发任何错误。任何遗漏的参数都会已****Undefined****传递给函数，多余的参数被忽略。**

 

****

 

Function类

 

所有函数都应看作是Function类的实例。使用Function类直接创建函数：

 

var fName = new Function(arg1,arg2,..,function_body);

 

eg：doAdd = new Function("iNum","alert(iNum+ 100)");

 

doAdd.length;//1 函数是引用类型，所以也有属性和方法，其属性length返回所期望的参数个数。

 

Funtion对象也有valueOf和toString方法，这2个方法返回的都是函数的源代码，在调试时尤其有用。

 

 

闭包

 

ECMASCript最容易让人误解的一点是其支持闭包。所谓闭包，是指词法表示包括不必计算的变量的函数，也就是说，该函数能使用函数外定义的变量。在ECMASCript中使用全局变量是个简单的闭包实例。
