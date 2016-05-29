---
author: me115wp
comments: true
date: 2011-02-08 07:14:49+00:00
layout: post
link: http://blog.me115.com/2011/02/106
slug: '%e3%80%8a%e5%a4%9a%e6%a0%b8%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e3%80%8bpart-6%ef%bc%9aopenmp-%e4%b8%80%e7%a7%8d%e5%8f%af%e7%a7%bb%e6%a4%8d%e7%9a%84%e5%a4%9a%e7%ba%bf%e7%a8%8b%e8%a7%a3%e5%86%b3'
title: 《多核程序设计》Part 6：OpenMP 一种可移植的多线程解决方案（2）
wordpress_id: 106
categories:
- 并行计算
- 读书笔记
tags:
- OpenMP
---

OpenMP对于循环语句中的循环有如下约束：

 

1.循环语句中的循环变量必须是有符号整型，对于无符号整型，将无法使用。（OpenMP2.5. 在3.0中将被取消）

 

2.循环语句中的比较操作必须是这样形式：< , <= , > ,>=

 

3.循环语句中的第3个表达式必须是整数加或者整数减操作，加减的数值必须是一个循环不变量。

 

4.如果比较操作是< , <=，那么寻找变量的值在每次迭代的时候都必须增加；相反，如果比较操作是>,>=，那么循环变量的值在每次迭代时都必须减少。

 

5.循环必须是单入口，单出口的。（如果使用了goto或break语句，那么它们的跳转范围必须在循环之内，不能跳出循环。exit是个例外）

 

**数据竞争：**

 

使用OpenMP时，很容易忽视数据竞争的存在。使用Intel线程检测器（Intel VTune(TM)性能分析工具的一个插件），可以辅助找到数据竞争。

 

默认情况，并行区中的所有变量都是共享的 ，但3种情况例外：

 

1.在paraller for循环中，循环索引是私有的；

 

2.并行区中的局部变量是私有的；

 

3.所有在private、firstprivate、lastprivate或reduction子句中列出的变量是私有的；

 

每当使用OpenMP并行化一个循环之前，应该仔细检查所有的存储访问，包括有被调用函数所发出的访存。

 

  

[](http://blog.csdn.net/drzhouweiming/archive/2007/10/26/1844762.aspx)

  **数据规约（reduction)：**

 

[http://www.cnblogs.com/me115/archive/2011/01/27/1946129.html](http://www.cnblogs.com/me115/archive/2011/01/27/1946129.html)

 

**降低线程开销：**

 

#pragma omp parallel for

 

for(k = 0 ;k < m ; i++){

 

fun1(k);

 

}

 

#pragma omp parallel for

 

for(k = 0 ;k < m ; i++){

 

fun2(k);

 

}

 

以上需要进入并行区2次，增大了线程开销，像这种相邻的，可进一步优化：

 

#pragma omp parallel

 

{

 

#pragma omp for

 

for(k = 0 ;k < m ; i++){

 

fun1(k);

 

}

 

#pragma omp for

 

for(k = 0 ;k < m ; i++){

 

fun2(k);

 

}

 

}

 

这样，会运行的更快，因为它只包含一次进入并行区域的开销。

 

**提高程序性能的设计方法**

 

使用Barrier和nowait

 

线程遇到栅障必须等待，直到并行区中的所有线程都到达同一点，再继续向下执行。在parallel/for/sections/single的结构的最后，会有一个隐式的栅障。可使用nowait子句除去这个隐式的栅障。

 

#pragma omp parallel for nowait 

 

OpenMP也支持使用barrier编译指导显式的使用栅障，

 

#pragma omp barrier

 

但线程和多线程交错执行：

 

int x[100];

 

#pragma omp parallel 

 

{

 

//每个线程都调用这个函数

 

int tid = omp_get_thread_num();

 

//这个循环被划分到多个线程上

 

#pragma omp for nowait

 

for(int k = 0; k < 100 ;i++)

 

x[k] = fn1(tid);

 

//上面这个循环的结束处不存在使所有线程进行同步的隐式栅障

 

#pragma omp master

 

y = fn_input_only();//只有主线程会调用这个函数，添加一个显式的栅障对所有的线程进行同步，从而确保x[0-99]处于就绪状态

 

#pragma omp barrier

 

//这个循环也被分到多个线程上

 

#pragma omp for nowait

 

for(k = 0; k < 100; k++)

 

x[k] = y + fn2(x[k] );

 

//上面的这个循环没有栅障，所有线程不会相互等待

 

// 某个线程（假设为第一个线程）在执行完上面的代码后将继续执行后续的代码

 

#pragma omp single

 

fn_single_print(y);//只有一个线程会调用这个函数

 

//上面这个single结构会有一个隐式栅障，所以会进行线程同步

 

#pragma omp master

 

fn_print_array(x);//只有一个线程会打印x[];

 

}

 

**数据的copy-in和copy-out**    <table cellpadding="0" border="1" cellspacing="0" ><tbody >       <tr >         
<td width="105" valign="top" >           

firstprivate

        
</td>          
<td width="649" valign="top" >           

使用变量在主线程的值对其在每个线程的对应私有变量进行初始化。

        
</td>       </tr>        <tr >         
<td width="105" valign="top" >           

lastprivate

        
</td>          
<td width="649" valign="top" >           

将最后一次迭代块中计算出来的私有变量复制出来，到主线程中。

        
</td>       </tr>        <tr >         
<td width="105" valign="top" >           

copyin

        
</td>          
<td width="649" valign="top" >           

将主线程的threadprivate变量的值复制到执行并行区的每个线程的threadprivate变量中。

        
</td>       </tr>        <tr >         
<td width="105" valign="top" >           

copyprivate

        
</td>          
<td width="649" valign="top" >           

使用一个私有变量将某个值从一个成员线程广播到执行并行区的其它线程。

        
</td>       </tr>     </tbody></table>

 

**保护变量的更新操作：**

 

用到的关键词：critical、atomic编译指导，用于保护共享变量的更新，避免数据竞争。

 

Intel OpenMP任务队列扩展：

 

Intel OpenMP任务队列扩展允许程序员对诸如递归函数、动态树搜索和指针跟踪这样的控制结构进行并行化。

 

#pragma omp parallel taskq shared(p)

 

#pragma intell omp task captureprivate(p)

 

**OpenMP代码调试：**

 

并行程序的调试关键是将问题定位到引发问题的较小代码段中。

 

调试OpenMP的一般指导性步骤：

 

1.通过启用和禁用程序中的OpenMP编译指导，使用二分搜索法找出引发故障的并行结构；

 

2.关闭/Qopenmp编译开关，使用/Qopenmp_stub 开关来编译引发错误的子程序；然后检查代码中的故障是否发生在串行执行的过程中；如果是，则进行串行代码调试，如果不是，继续；

 

3.使用/Qopenmp开关编译引发错误的子程序，并设置环境变量OMP_NUM_THREADS=1，然后检查多线程代码是否在串行执行的时候发生故障。如果是，则进行多线程代码的单线程调试；如果不是，继续；

 

4.使用/Qopenmp以及/OD、/O1、/O2、/O3或/Oipo中的某一个编译开关编译代码，在最低优化等级上找出故障场景。

 

5.检查引起错误的代码段，查找诸如并行化后数据相关性被破坏、数据竞争、死锁、缺少栅障和变量未初始化之类的问题。如果还没找到，继续；

 

6.使用/Qtcheck开关进行OpenMP代码插桩，并在Intel线程检查工具中运行插桩后的代码。

 

**错误分析：**

 

1.问题一般都是由数据竞争引起的。大多数数据竞争的产生是因为某些共享变量本来应该被声明为私有变量、规约变量或线程私有变量。

 

默认情况下，在栈结构中声明的变量是私有的，但c/c++关键字static可以将变量放置在全局堆中，并因此成为OpenMP循环中的共享变量。

 

使用default（none）子句可以帮助找到那些难以定位的变量。如果指定了default(none)，那么每个变量都必须使用一条数据共享属性子句来声明。

 

#pragma omp parallel for default(none) private(x,y) shared(a,b)

 

2.另一种常见错误就是变量未初始化。记住，默认，进入或退出并行区中的变量都是未定义的。

 

3.可能因为并行成分太多引起错误。禁用某些并行代码，让其串行执行也是很有用的。使并行区以串行方式执行的一种简单方式就是使用if子句，该子句可以加到任何并行结构上：

 

#pragma omp parallel for if(0)

 

printf("");

 

#pragma omp parallel for if(n>=16)

 

for(k = 0; k< n;k++) fn2(k);

 

在迭代次数小于16时，以上代码将串行执行。

 

4.其它方法：找出包含错误的那部分代码，并将其放入一个临界段、一个single结构或一个master结构中。然后找出临界段中可以正确执行，在临界段外或单线程执行的时候就会出错的代码。

 

OpenMP提供了在不重写代码的情况下进行代码测试的可能性。而windows api或pthread所使用的标准程序设计技术则将代码局限于某种多线程模型，从而调试更加困难。
