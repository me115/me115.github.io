---
layout: wiki
title: 字符串
categories: [python]
description: some word here
keywords: keyword1, keyword2
---



## 取子串
```python
# 取开头结尾
if not cwd.endswith('/twemproxy_bin'):
# 取开头
cmd.startswith
```

关于切片：
stringstr[A:B:C]
A为取字串的开始索引；
B为取出子串后剩下部分的开始索引；
C为步长；


## 字符串切分
```python
s.split('/')
# 以上只支持一个分隔符，要指定多个，使用re：
import re
re.split('; |, ',str)
# eg:
a='Beautiful, is; better*than\nugly'
import re
re.split('; |, |\*|\n',a)
['Beautiful', 'is', 'better', 'than', 'ugly']
```


## 过滤字符及字符串
删除s字符串中开头、结尾处，位于 rm删除序列的字符:
```python
#当rm为空时，默认删除空白符（包括'\n', '\r',  '\t',  ' ')
s.strip(rm) 
# 只删除右边的
rstrip()
# 过滤掉不可见字符
line = re.sub(r'[\x00-\x1f]','',line)
```


## 字符串查找
```python
nPos = sStr1.index(sStr2)
```
注：index查找不到会报错，如果只是确定是否有子串，可以使用count() 返回个数，没找到返回0；


## 字符串替换
```python
# 字符串替换
str.replace("abc","bcd")
# 去除空格
od = od.replace('\n','')
# 不可见字符替换
line = re.sub('\x02',';02;', line)
# 字符串反转
def reverse_str( s ):
    return s[::-1]
# NP
def reverse_str( s ):
    t = ''
    for x in xrange(len(s)-1,-1,-1):
        t += s[x]
    return t
```



## 字符串格式化

```python
print "Hello %(name)s !" % {'name': 'James'}
Hello James !
print "I am years %(age)i years old" % {'age': 18}
I am years 18 years old
print "Hello {name} !".format(name="James")
Hello James !
#浮点输出  0-4的长度使用左对齐  <表示左对齐：
print '{0:<4.2f}'.format(1.1415926)
# 多个字符串连接
print '{}employees/{}/'.format(RATAK_API, email)
```

format字符串格式化代码

>
> 格式	描述
> %%	百分号标记
> %c	字符及其ASCII码
> %s	字符串
> %d	有符号整数(十进制)
> %u	无符号整数(十进制)
> %o	无符号整数(八进制)
> %x	无符号整数(十六进制)
> %X	无符号整数(十六进制大写字符)
> %e	浮点数字(科学计数法)
> %E	浮点数字(科学计数法，用E代替e)
> %f	浮点数字(用小数点符号)
> %g	浮点数字(根据值的大小采用%e或%f)
> %G	浮点数字(类似于%g)
> %p	指针(用十六进制打印值的内存地址)
> %n	存储输出字符的数量放进参数列表的下一个变量中


## 其他
100个空格的字符串：
```python
spaces = 100 * " "
```
