---
layout: post
title: Kerberos是怎么工作的？
categories: [cate1, cate2]
description: some word here
keywords: keyword1, keyword2
---


Kerberos是一种计算机网络授权协议，用来在非安全网络中，对个人通信以安全的手段进行身份认证。
采用客户端/服务器结构，并且能够进行相互认证，即客户端和服务器端均可对对方进行身份认证。

## 关键要素
KDC:Key Distribution Center
– Each user and service shares a secret key with the KDC
– The KDC generates and distributes session keys
– Communicating parties prove to each other that they

KDC 包含两部分：

– Authentication Server (AS)
	● Issues “Ticket-Granting Tickets” (TGT)
– Ticket Granting Server (TGS)
	● Issues service tickets

## 交互过程
1. client向KDS发用户名和服务端名称
2. KDC回应TGT，使用用户密码加密；
3. client输入密码，解密TGT
4. 到手的TGT，用来和KDC通信（我已经有TGT了，属于被你信任的列表中，给我我想要的东西吧），进一步获取service tickets.
   img:k1.jpg
5. KDC返回用户service tickets，
6. 用户拿service tickets和Server通信，请求授权；
7. server端授权完成，建立session；

交互流程图：
![](images/blog/kerbos1.jpg)

## 用户命令
- kinit # 与KDC通信，请求授权
- klist # 查看已授权列表

```shell
colin@n6-131-078:~$ klist
Ticket cache: FILE:/tmp/krb5cc_1098
Default principal: colin@xxx.COM

Valid starting       Expires              Service principal
06/21/2016 11:43:29  06/22/2016 11:43:26  krbtgt/xxx.COM@xxx.COM # TGT
06/21/2016 11:43:45  06/22/2016 11:43:26  host/10.6.26.137@       # host service ticket
06/21/2016 11:43:45  06/22/2016 11:43:26  host/10.6.26.137@xxx.COM
```


## Kerberos principals
Clients (users or services) are identified by “principals”
Principals look like: primary/instance@realm

– Primary: user or service name
– Instance: optional for user principals, but required for service principals
– Realm: the Kerberos realm

Examples:

– User: joe@FOO.COM
– Service: imap/bar.foo.com@FOO.COM


## 如何搭建一个Kerberos KDC？
准备工作：

1. Configure NTP (time synchronization) across all machines
2. Configure DNS
3. Configuration files

   – /etc/krb5.conf
   – /etc/kadm5.acl
4. Prepare the Kerberos database
   – Initialize the Kerberos database
   – Add administrator's principal
   – Start the KDC and KDC administration processes

5. Create user principals
   – Note: service principals are created when configuring your other services to support Kerberos authentication

## 如何搭建一个Kerberos  客户端？

- Configuration file
  /etc/krb5.conf
  You can just copy this from the KDC

- Service principals
- PAM (Pluggable Authentication Modules) （重点是这个，我们需要配置登录到服务器，就配置这个）
  – Needed if you want to be able to authenticate users logging into this machine via Kerberos


## 参考
- http://www.logicprobe.org/~octo/pres/pres_kerberos.pdf
- http://web.mit.edu/kerberos/krb5-1.12/doc/index.html


