---
author: me115wp
comments: true
date: 2011-02-10 05:39:23+00:00
layout: post
link: http://blog.me115.com/2011/02/115
slug: '%e3%80%8ampi%e5%b9%b6%e8%a1%8c%e7%a8%8b%e5%ba%8f%e8%ae%be%e8%ae%a1%e5%ae%9e%e4%be%8b%e6%95%99%e7%a8%8b%e3%80%8bpart1-mpi%e5%b9%b6%e8%a1%8c%e7%8e%af%e5%a2%83%e5%8f%8a%e7%bc%96%e7%a0%81%e6%a8%a1'
title: 《MPI并行程序设计实例教程》PART1.MPI并行环境及编码模型
wordpress_id: 115
categories:
- 并行计算
- 读书笔记
tags:
- MPI
---

 

**_1.并行编程模式-消息传递：_**

 

具有通用功能的消息传递库有PICL、PVM、PARMACS、P4、MPI等；面向特定系统定制的消息传递库有MPL、NX、CMMD等。

 

消息传递模型的主要缺点是：要求在编程过程中参与显式的数据划分和进程间同步，因此会需在解决数据依赖、预防死锁上话费较大精力。

 

**_2.MPI消息传递通信的基本概念_**

 

  
  * 缓存区
 

MPI环境定义了3种缓存区：

 

  <table cellpadding="0" border="1" cellspacing="0" ><tbody >       <tr >         
<td width="174" valign="top" >           

应用缓冲区

        
</td>          
<td width="589" valign="top" >           

指保存将要发送或接受的数据的地址空间，既消息格式定义的内容部分。

        
</td>       </tr>        <tr >         
<td width="174" valign="top" >           

系统缓冲区

        
</td>          
<td width="589" valign="top" >           

MPI环境为通信所准备的存储空间。

        
</td>       </tr>        <tr >         
<td width="174" valign="top" >           

用户向系统注册的缓冲区

        
</td>          
<td width="589" valign="top" >           

指用户使用某些API（MPI_Bsend)时，在程序中显式申请的存储空间，然后注册到MPI环境中供通信使用。

        
</td>       </tr>     </tbody></table>

 

  
  * 通信子（communicator）
 

通信子是MPI环境管理进程及通信的基本设施。（eg：MPI_COMM_WORLD)。对某个进程的操作必须放在通信子内方可有效。

 

  
  * 通信协议：
 

MPI环境采用以下通信协议：

 

  <table cellpadding="0" border="1" cellspacing="0" ><tbody >       <tr >         
<td width="159" valign="top" >           

立即通信协议（Eager）

        
</td>          
<td width="650" valign="top" >           

总是假定目标进程具备保存消息数据的能力。注：该方式减少了同步延迟，简化编程，但需要相当数量的缓冲区。

        
</td>       </tr>        <tr >         
<td width="159" valign="top" >           

集中通信协议（Rendezvous）

        
</td>          
<td width="650" valign="top" >           

在目标准备好后，才可执行发送动作。注：该方式可确保可靠和安全，并提供消除多次数据的可能，但增加了编程复杂性（需结合wait/test等机制），同时也带来了同步延迟（等待进程许可需要时间）。

        
</td>       </tr>        <tr >         
<td width="159" valign="top" >           

断消息协议（Short）

        
</td>          
<td width="650" valign="top" >           

消息数据与信封封装在一起发送。

        
</td>       </tr>     </tbody></table>

 

From:《MPI并行程序设计实例教程》

 

张武生 等著 清华大学出版社
