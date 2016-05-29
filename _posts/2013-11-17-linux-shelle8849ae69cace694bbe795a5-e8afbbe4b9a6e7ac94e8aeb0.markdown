---
author: me115wp
comments: true
date: 2013-11-17 11:08:09+00:00
layout: post
link: http://blog.me115.com/2013/11/410
slug: linux-shell%e8%84%9a%e6%9c%ac%e6%94%bb%e7%95%a5-%e8%af%bb%e4%b9%a6%e7%ac%94%e8%ae%b0
title: Linux Shell脚本攻略 读书笔记
wordpress_id: 410
categories:
- Linux&amp;Unix
- Linux工具箱
tags:
- linux
- shell脚本
---

Linux Shell脚本攻略 读书笔记  
![Linux Shell脚本攻略](http://www.me115.com/upload/book/201311/16nov2013215210.jpg)





这是一本小书，总共253页，但内容却很丰富，书中的示例小巧而实用，对我这样总是在shell门前徘徊的人来说真是如获至宝；  
最有价值的当属文本处理，对这块我单独整理出来一篇blog，详见[《Linux Shell文本处理工具集锦》](http://blog.me115.com/2013/11/398)  
下面是文本处理之外的简单介绍，如果你觉得自己的shell需要充充电，强烈建议读读这本[《linux Shell脚本攻略》](http://www.amazon.cn/Linux-Shell%E8%84%9A%E6%9C%AC%E6%94%BB%E7%95%A5-%E6%8B%89%E5%85%8B%E4%BB%80%E6%9B%BC/dp/B0060FSIE4?SubscriptionId=AKIAJOMEZLLKFEWYT4PQ&tag=z08-23&linkCode=xm2&camp=2025&creative=165953&creativeASIN=B0060FSIE4)。





## 嗨，Echo一下





从 echo开始：  
带引号的 echo和不带引号的 echo区别：  
使用带引号时，bash 不会对单引号中的变量进行求值，而是原样输出；  
而使用双引号，或者不使用引号，则会对变量进行解析：




    
    <code>echo '$var' // $var
    echo $var // 5
    echo "$var" // 5</code>







  * 对比printf 的格式化输出  
printf "%-5s %-10s %-4s" NO NAME HELLO //左对齐 宽度为 5 10 4  
具体的值使用右边引号外的； 

  * 


对比 python中的输出：  
printf " %s " % "hello"



  * 


让echo支持转义字符：-e
    
    <code>[/home/weber#]echo -e '1\t2'
    1    2</code>





## 给终端来点颜色







  * 


在终端彩色输出：
    
    <code>echo -e '\e[1;31m this is red text\e[0m'
    this is red text</code>



  * 


更有用的是为我们提示符着色：  
vi .profile添加：  
export PS1='[[\e[34;1m]$PWD#\e[0m]'  
![image](http://blog.me115.com/wp-content/uploads/2013/11/image.png)



  * 


注意环境变量的赋值错误  
var=value //赋值操作 echo $var  
var = value //判断相等操作
    
    <code>[/home/weber#]var=5
    [/home/weber#]echo 'this is $var'
    this is $var
    [/home/weber#]echo "this is $var"
    this is 5</code>



  * 


获取变量值的长度： 
    
    <code>  length=${#var} //语法真诡异</code>



  * 打印程序的退出状态： 
    
    <code>  echo $?</code>



  * 对.bashrc 修改的简便方法：
    
    <code>  echo 'a=/abc/' >> ~/.bashrc</code>



  * 为rm打造回收站功能：
    
    <code>  alias rm='cp $@ ~/backup; rm $@' </code>





## shell的控制结构







  * 


条件判断
    
    <code class="lang-sh">if condition;
    then
    commands;
    elif condition;
    then
    commands
    else
    commands
    fi</code>



  * 


循环结构
    
    <code>for var in list;
    do
    commands;
    done</code>




list可以是字符串或是序列；  
echo {1..50} 生成列表  
echo {a..z}



  * 


更亲切的for循环（类C）
    
    <code>for((int i = 0 ; i < 10 ;i++))
    {
    commands;
    }</code>



  * 


while循环
    
    <code>while condition
    do
    commands;
    done</code>





## 算术比较







  * 


语法  
if[ $var -eq 0 ] && action;  
-gt :大于  
-lt: 小于  
-ge: 大于等于  
-le: 小于等于  
-a: 逻辑与  
-o: 逻辑或



  * 


字符串的比较，最好使用双中括号  
[[ $str1 == $str2 ]]  
[[ $str != $str ]]  
支持 > 、 <  
判空：-z 非空： -n 



  * 


if更友好：  
if [[ -n $str ]] && [[ -z $str2 ]];  
then  
commands;  
fi



  * 


避免if语句过长  
[ condition ] && action ;//condition为真，则执行action  
[ condition ] && action; // condition 为假，则执行aciton  
注意：condition 和[ ] 之间必须有空格，否则报错；





## 目录操作







  * 


创建长路径目录：
    
    <code>  mkdir -p colin/soft/redis/</code>



  * 


粘滞位和setuid：
    
    <code>  chmod a+t file_dir/</code>




设置了粘滞位，只有目录的所有者才有权限删除该目录



  * 


让所有用户都有权限执行文件:
    
    <code>  chown root.root file 
      chmod +s file</code>




设置setuid文件权限后，它运行其它用户以文件所有者身份来执行文件；  
tips：只有linux 的elf二进制文件才可设置这个文件权限；



  * 


文件写保护
    
    <code>  chattr +i file //文件不可修改、不可删除</code>



  * 


touch的妙用  
touch可用来生成空白文件；如果文件存在，则更新时间戳；  
eg：批量生成100个空白文件
    
    <code>for name in test{0..100}.txt
    do
    touch $name
    done</code>



  * 


只列出目录的方法




    1. ls -d */ 

    2. ls -F| grep '/$' //-F会在文件尾部列出文件类型； 

    3. ls -l| grep '^d' //-l第一行第一个字符是文件类型； 

    4. find . -type d -maxdepth 1 -print 




  * 切换目录快速定位  
压入并切换：
    
    <code> pushd /var/www
     dirs： 显示当前路径栈</code>

选择路径回切（通过索引编号）：
    
    <code> pushd +3</code>

移除最近压入栈的路径并切换到下一个目录：
    
    <code> popd</code>





## 网站下载





wget url：直接下载文件或者网页；  
--limit-rate :下载限速，别太快  
-o：指定日志文件；输出都写入日志；  
-c：断点续传




    
    <code>    wget -c ftpUrl</code>





### 下载整个站点所有页面




    
    <code>wget --mirror me115.com</code>





或者：




    
    <code>wget -r -N -l DEPTH me115.com</code>





-l:指定页面层级的深度；  
-N：允许对文件使用时间戳；





### 格式化文本形式下载网页




    
    <code>lynx -dump URL > web.txt</code>





-dump选项将网页已ASCII字符形式下载到文本文件中；





### 更多





如果需要更丰富的下载功能，考虑使用curl；其支持多种协议，  
还支持POSF、cookie、认证、用户代理字符串等特性；  
如果你想将网页处理流程自动化，cURL是很好的选择；





## tar 归档工具







  * 


归档：
    
    <code>  tar -cvf output.tar dir/</code>




-c：创建规定  
-f：指定文件名  
-v：在归档或解开时显示更多的详细信息



  * 


-r：追加文件到归档中：
    
    <code>  tar -rvf output.tar dir2/</code>



  * 


-t:显示归档内容；
    
    <code>  tar -tf output.tar</code>



  * 


提取归档文件：
    
    <code>  tar -xvf output.tar</code>



  * 


归档时排序版本控制目录（svn、cvs、git等目录信息）：
    
    <code>  tar --exclude-vcs -czvf source_code.tar.gz source/</code>





## rsync 备份系统快照




    
    <code>rsync -av source_path destination_path</code>





-a：表示归档；-v：归档时显示详细信息  
-z：指定在网络传输时使用数据压缩；  
路径可以是远程路径：  
eg:rsync -avz source_dir usrname@host:path;  
(如果不希望使用交互式的密码输入，可使用SSH密钥来实现）





注：source_dir末尾如果使用路径/，那么rsync会将source_dir目录中的所有  
内容复制到目的端；如果没有带/，则会将source_dir本身复制到目的端；





备份时排除部分文件：




    
    <code>rsync -avz /home/code /mnt/disk/bakup --exclude "*.txt"</code>





可使用一个列表文件指定需要排除的的文件：  
--exclude-from FILEPATH





## ftp自动传输





使用ftp选项-i关闭交互会话；  
eg ftp.sh：




    
    <code>!/bin/bash
    HOST='me115.com'
    USER='colin'
    PASSWD='passwd'
    ftp -i -n $HOST <<EOF
    user ${USER} ${PASSWD}
    binary
    cd /home/linux
    put test.php
    quit
    EOF</code>





## 磁盘管理







  * 显示文件大小
    
    <code>  du -h filename</code>



  * 


统计文件夹的详细大小及总计
    
    <code>  du -ch svn_archives/</code>




-s 只输出合计信息；



  * 


找出指定目录中最大的文件
    
    <code>  du -ak source_dir | sort -nrk 1| head</code>




-a:扫描指定目录下所有文件（递归到最深一级目录）  
结果单位指定：-k KB -m MB -h 人性化显示（打算排序的时候，不要用这个，因为单位不统一了）  
上述结果中包含了目录，如果只看文件，不需要目录：
    
    <code>  find . -type f -exec du -k {} \; | sort -nrk 1| head</code>





## 故障排查







  * 当前登录用户：who 

  * 当前登录主机的用户列表：users 

  * 排除重复用户：users | tr ' ' '\n' | sort |uniq 

  * 系统运行时长: uptime 

  * 获取登录会话信息：last (单个用户 last colin） 

  * 列出系统开放端口及运行的服务:
    
    <code>lsof -i
    或者：
    netstat -tnp</code>





## 使用syslog记录日志





向syslog文件/var/log/messages中记录日志信息：




    
    <code>logger hello,this is colin</code>





发送带标记的日志信息




    
    <code>logger -t ME115  hello,colin coming</code>





/etc/rsyslog.d/下配置了标记和日志的对应关系；





## 杀死进程







  * 通过进程名获取进程id
    
    <code>  ps -C command_name</code>



  * 通过命令名终止进程：
    
    <code>  killall process_name</code>



  * 


通过名称强杀进程：
    
    <code>  kill -9 process_name</code>



  * 


找出命令所在位置：　
    
    <code>  which php
      whereis php</code>



  * 


列出命令简短描述信息：
    
    <code>  whatis ls</code>





## 用/proc收集信息







  * 获取cpu信息: 
    
    <code>  cat /proc/cpuinfo</code>



  * 获取内存信息: 
    
    <code>  cat /proc/meminfo</code>



  * 获取分区信息: 
    
    <code>  cat /proc/partitions</code>

每一个运行的进程在/proc下都有一个以该进程id命名的目录，  
/proc/PID/下的重要文件：  
environ:包含与进程相关的环境变量；  
exe：到进程工作目录的符号链接；  
fd：进程所使用的文件描述符 




Posted by: 大CC | 18NOV,2013  
博客：[blog.me115.com](http://blog.me115.com)  
微博：[新浪微博](http://weibo.com/bigcc115)



