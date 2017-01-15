---
layout: wiki
title: 读写文件
categories: [python]
description: some word here
keywords: keyword1, keyword2
---

## 打开文件写入
```python
fw = open('maxer_report.txt','w')
for str in self.max_list:
    fw.writelines('%s\n' % str )
fw.close()
```


## 读取文件
```python
datafile = open('datafile',"r")
for line in datafile: # 对文本文件，可以直接遍历文件对象获取每行
    do_something(line)

# 按行读取
file_object = open('datefile','r')
list_of_all_the_lines = file_object.readlines( )
for line in list_of_all_lines;

# 将文件读入到一个字符串中
f = open('datafile','r')
s = f.read()
>> s
"test line1\n test line2 \n end:"

# 读取二进制文件
with open('/root/client.conf', mode='rb') as file:
    fileContent = file.read()

# 取文件名
file.split('.')[0]

# 取文件后缀
file.split('.')[-1]

# 判断文件是否存在
import os
if os.path.exists(filename):
    print 'file exist'
else
    print 'file not exists'

# 判断是文件
os.path.isfile(filename):
# 判断是文件夹
os.path.isdir(filename) # 需要连接path: os.path.join(dir,filename)
# 判断是否绝对路径：
os.path.isabs(filepath)

# 遍历文件夹
for filename in os.listdir(dir):
	# 判断文件夹，需要join path目录
    if os.path.isdir(os.path.join(dir,filename)): 

# 获取当前系统路径
print os.getcwd()

# 获取文件夹下所有文件
import re
for filename in os.listdir('tmp/'):

#　获取当前文件夹目录
$PYTHON -c 'import os;os.path.dirname(os.path.abspath(__file__))'
```

## 删除文件
```python
删除文件：
os.remove('tmp/ua.tmp')
or
import os
os.popen('rm -rf /tmp/tmpabc')

删除文件夹：
os.rmdir('tmp/')
```

## 更改文件/文件夹读写权限
```python
# 具有写文件夹的权限
os.chmod(os.path.join(dir,filename), stat.S_IWRITE |stat.S_IREAD)
```

## 复制文件
```python
#使用shutil来实现文件的拷贝
import shutil

shutil.copyfile("myfile1.txt", "myfile2.txt")
shutil.move("myfile1.txt", "../")                 #把myfile1.txt移动到当前目录的父目录，然后删除myfile1.txt
shutil.move("myfile2.txt", "myfile3.txt") #把myfile2.txt移动到当前目录并重命名myfile3.txt
```

