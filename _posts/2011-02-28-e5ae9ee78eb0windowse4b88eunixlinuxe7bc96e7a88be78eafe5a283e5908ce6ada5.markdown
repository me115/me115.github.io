---
author: me115wp
comments: true
date: 2011-02-28 09:52:06+00:00
layout: post
link: http://blog.me115.com/2011/02/137
slug: '%e5%ae%9e%e7%8e%b0windows%e4%b8%8eunixlinux%e7%bc%96%e7%a8%8b%e7%8e%af%e5%a2%83%e5%90%8c%e6%ad%a5'
title: 实现windows与Unix/Linux编程环境同步
wordpress_id: 137
categories:
- Linux&amp;Unix
---

本文通过以下几步设置，实现在Windows下使用VS编程和调试，最终程序在Unix上运行。

 

需要的软件如下：

 

cwRsync：客户端/服务器端同步软件

 

对于cwRsync的安装，这里不多做介绍，更多请参考：

 

[http://www.cnblogs.com/me115/archive/2011/02/28/1967213.html](http://www.cnblogs.com/me115/archive/2011/02/28/1967213.html)

 

[http://www.cnblogs.com/me115/archive/2011/02/28/1967214.html](http://www.cnblogs.com/me115/archive/2011/02/28/1967214.html)

 

Windows服务器上装的是cwRsync_Server_2.1.5_Installer.exe(最新版有4.**，更易于安装）

 

Unix上默认不安装rsync，本机上安装的是3.0.7;

 

**_一. cwRsync设置_**

 

使用本地开发的机器作为cwRsync的服务器，安装服务器版本，在rsyncd.conf中设置程序所在路径:

 

[test]

 

path = /cygdrive/d/OnDEVing/UnixAP/UnixAP

 

read only = false

 

transfer logging = yes

 

**_二. Unix机器上脚本设置_**

 

在Unix的bin目录下建立2个脚本tow、tou:

 

tow:

 

/userhome/jhuang/soft/rsync/bin/rsync -vzrtopgu --progress --delete /userhome/jhuang/cltest/unixAP/ colin@172.22.2.58::test 

 

tou:

 

/userhome/jhuang/soft/rsync/bin/rsync -vzrtopgu --progress --delete colin@172.22.2.58::test /userhome/jhuang/cltest/unixAP/

 

rsync 参数说明如下：

 

-vzrtopgu：-v：-verbose 详细模式输出；-z:压缩传输；-u, --update 仅仅进行更新，也就是跳过所有已经存在于DST，并且文件时间晚于要备份的文件。(不覆盖更新的文件)；其它参数是保持文档属性及属组不变；

 

--progress是指显示出详细的进度情况

 

--delete是指如果服务器端删除了这一文件,那么客户端也相应把文件删除

 

colin@172.22.2.58中的colin是指定密码文件中的用户名

 

::test是指在rsyncd.conf里定义的模块名

 

/userhome/jhuang/cltest/unixAP/是指本地要备份目录

 

（对于备份模式：参数使用-av）

 

**_三. 编写程序规则_**

 

使用VS建立的项目会包含一个stdafx.h文件，是预编译文件，对于在unix机器上运行的文件，我们是不需要它的。这里为了减少程序代码修改量，对于包含Unxi系统调用的cpp文件，需要包含这个文件。

 

1.stdafx.h内容：

 

 
    
    #pragma once
    
    #if defined(_WIN32) || defined(WIN32)
    
    // windows平台
    
    #else
    
    #define UNIX_PLATFORM 67800
    
    #endif





2.包含unix系统调用的文件（eg：fig1_3.cpp)：




    
    #include "fig1_3.h"
    
    #include <iostream>
    
    #include "stdafx.h"
    
    #ifdef UNIX_PLATFORM
    
    #include "include/apue.h"
    
    #include <dirent.h> // 这里的库是unix系统下所属
    
    #endif
    
    void fig1_3::EgExe1(string strparam)
    
    {
    
    #ifdef UNIX_PLATFORM
    
    DIR *dp;
    
    struct dirent *dirp;
    
    if (strparam.empty())
    
    err_quit("usage: ls directory_name");
    
    if ((dp = opendir(strparam.c_str())) == NULL)
    
    err_sys("can't open %s", strparam.c_str());
    
    while ((dirp = readdir(dp)) != NULL)
    
    printf("%sn", dirp->d_name);
    
    closedir(dp);
    
    exit(0);
    
    #endif
    
    }





**_四 总结_**





通过以上几步设置，即可实现与系统调用无关的代码在VS中编写和调试；对于需要调用unix库函数的代码，可使用 tou脚本命令，将最新代码同步到unix机器上，再调试运行。





附：





**_rsync 命令详细参数全说明：_**





-v, --verbose 详细模式输出





-q, --quiet 精简输出模式





-c, --checksum 打开校验开关，强制对文件传输进行校验





-a, --archive 归档模式，表示以递归方式传输文件，并保持所有文件属性，等于-rlptgoD





-r, --recursive 对子目录以递归模式处理





-R, --relative 使用相对路径信息





-b, --backup 创建备份，也就是对于目的已经存在有同样的文件名时，将老的文件重新命名为~filename。可以使用--suffix选项来指定不同的备份文件前缀。





--backup-dir 将备份文件(如~filename)存放在在目录下。





-suffix=SUFFIX 定义备份文件前缀





-u, --update 仅仅进行更新，也就是跳过所有已经存在于DST，并且文件时间晚于要备份的文件。(不覆盖更新的文件)





-l, --links 保留软链结





-L, --copy-links 想对待常规文件一样处理软链结





--copy-unsafe-links 仅仅拷贝指向SRC路径目录树以外的链结





--safe-links 忽略指向SRC路径目录树以外的链结





-H, --hard-links 保留硬链结 -p, --perms 保持文件权限





-o, --owner 保持文件属主信息 -g, --group 保持文件属组信息





-D, --devices 保持设备文件信息 -t, --times 保持文件时间信息





-S, --sparse 对稀疏文件进行特殊处理以节省DST的空间





-n, --dry-run现实哪些文件将被传输





-W, --whole-file 拷贝文件，不进行增量检测





-x, --one-file-system 不要跨越文件系统边界





-B, --block-size=SIZE 检验算法使用的块尺寸，默认是700字节





-e, --rsh=COMMAND 指定使用rsh、ssh方式进行数据同步





--rsync-path=PATH 指定远程服务器上的rsync命令所在路径信息





-C, --cvs-exclude 使用和CVS一样的方法自动忽略文件，用来排除那些不希望传输的文件





--existing 仅仅更新那些已经存在于DST的文件，而不备份那些新创建的文件





--delete 删除那些DST中SRC没有的文件





--delete-excluded 同样删除接收端那些被该选项指定排除的文件





--delete-after 传输结束以后再删除





--ignore-errors 及时出现IO错误也进行删除





--max-delete=NUM 最多删除NUM个文件





--partial 保留那些因故没有完全传输的文件，以是加快随后的再次传输





--force 强制删除目录，即使不为空





--numeric-ids 不将数字的用户和组ID匹配为用户名和组名





--timeout=TIME IP超时时间，单位为秒





-I, --ignore-times 不跳过那些有同样的时间和长度的文件





--size-only 当决定是否要备份文件时，仅仅察看文件大小而不考虑文件时间





--modify-window=NUM 决定文件是否时间相同时使用的时间戳窗口，默认为0





-T --temp-dir=DIR 在DIR中创建临时文件





--compare-dest=DIR 同样比较DIR中的文件来决定是否需要备份





-P 等同于 --partial





--progress 显示备份过程





-z, --compress 对备份的文件在传输时进行压缩处理





--exclude=PATTERN 指定排除不需要传输的文件模式





--include=PATTERN 指定不排除而需要传输的文件模式





--exclude-from=FILE 排除FILE中指定模式的文件





--include-from=FILE 不排除FILE指定模式匹配的文件





--version 打印版本信息





--address 绑定到特定的地址





--config=FILE 指定其他的配置文件，不使用默认的rsyncd.conf文件





--port=PORT 指定其他的rsync服务端口





--blocking-io 对远程shell使用阻塞IO





-stats 给出某些文件的传输状态





--progress 在传输时现实传输过程





--log-format=formAT 指定日志文件格式





--password-file=FILE 从FILE中得到密码





--bwlimit=KBPS 限制I/O带宽，KBytes per second -h, --help 显示帮助信息
