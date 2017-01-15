---
layout: wiki
title: 日期时间
categories: [python]
description: some word here
keywords: keyword1, keyword2
---

## 获取当前时区日期
```python
from datetime import datetime
now = datetime.now()
```

## 日期计算
```python
#日期加减
from datetime import datetime
from datetime import timedelta
delta = timedelta(days=2)
n_days = now + delta
# 加几个月months
def add_months(dt,months):
    month = dt.month - 1 + months
    year = dt.year + month / 12
    month = month % 12 + 1
    day = min(dt.day,calendar.monthrange(year,month)[1])
    return dt.replace(year=year, month=month, day=day)
```

## 日期和字符串的转换
```python
# 格式化日期字符串 (30MAR14)
now.strftime('%d%b%y').upper()  ：22JUL14
now.strftime('%Y-%m-%d %H:%M:%S')  # '2012-03-05 16:26:23'
##将字符串转换为日期 string => datetime
print datetime.datetime.strptime(t_str,'%Y-%m-%d %H:%M:%S')
# 将2015-02-25 转换为20150225
a = '2015-02-25 00:00:00'
print datetime.datetime.strptime( a.split()[0],'%Y-%m-%d').strftime('%Y%m%d') # '20150225'

# 获取星期和周
week = datetime.strptime(i,'%Y-%m-%d').isocalendar()
（2015，52，5） # 2015年52周，星期5
```

格式化代码参考
>
> %a 星期几的简写
> %A 星期几的全称
> %b 月分的简写
> %B 月份的全称
> %c 标准的日期的时间串
> %C 年份的后两位数字
> %d 十进制表示的每月的第几天
> %D 月/天/年
> %e 在两字符域中，十进制表示的每月的第几天
> %F 年-月-日
> %g 年份的后两位数字，使用基于周的年
> %G 年分，使用基于周的年
> %h 简写的月份名
> %H 24小时制的小时
> %I 12小时制的小时
> %j 十进制表示的每年的第几天
> %m 十进制表示的月份
> %M 十时制表示的分钟数
> %n 新行符
> %p 本地的AM或PM的等价显示
> %r 12小时的时间
> %R 显示小时和分钟：hh:mm
> %S 十进制的秒数
> %t 水平制表符
> %T 显示时分秒：hh:mm:ss
> %u 每周的第几天，星期一为第一天 （值从0到6，星期一为0）
> %U 第年的第几周，把星期日做为第一天（值从0到53）
> %V 每年的第几周，使用基于周的年
> %w 十进制表示的星期几（值从0到6，星期天为0）
> %W 每年的第几周，把星期一做为第一天（值从0到53）
> %x 标准的日期串
> %X 标准的时间串
> %y 不带世纪的十进制年份（值从0到99）
> %Y 带世纪部分的十制年份
> %z，%Z 时区名称，如果不能得到时区名称则返回空字符。
> %% 百分号

## Django 中的日期
### 获取当前日期
```python
# 在settings.py 中社会当前时区：
TIME_ZONE = 'Asia/Shanghai'

# 使用timezone.localtime 将UTC 时间转为当前时间
from django.utils import timezone
dt1 = datetime.datetime.now() # 这个函数获取的就是当前的系统时间
print 'date1:', str(dt1)

dt2 = timezone.now() # timezone.now() 获取到的utc时间
print 'date2:', str(dt2)
dt3 = timezone.localtime(timezone.now())  #　要显示的需要转换到当前时区时间
print 'date2:', str(dt3)

#output：
> date1: 2016-11-21 18:09:38.575713
> date2: 2016-11-21 10:09:38.575772+00:00
> date3: 2016-11-21 18:09:38.575792+08:00

# 将UTC 时间转为当前时间后显示
timezone.localtime(notice.create_time).strftime("%Y-%m-%d %H:%M:%S")
```

## MYSQL中 的时区

mysql使用的是系统时区，系统默认配置的是东八区

```shell
mysql -h 10.4.21.169 -P1234 -umyname -p mypwd
mysql> SELECT @@global.time_zone, @@session.time_zone;
+--------------------+---------------------+
| @@global.time_zone | @@session.time_zone |
+--------------------+---------------------+
| SYSTEM             | SYSTEM              |
+--------------------+---------------------+
1 row in set (0.00 sec)
```
这样， 存放到DB中的时间显示的为UTC时间，比如上面三种方式的获取到的时间存到DB中，从DBshell中显示的是UTC时间：
```shell
date1: 2016-11-21 18:13:16.036195
DB shows： 2016-11-21 10:13:16.036195

date2: 2016-11-21 10:18:17.155390+00:00
DB shows: 2016-11-21 10:18:17.155390

date3: 2016-11-21 18:17:15.679798+08:00
DB shows：2016-11-21 10:17:15.686597
```

## 睡眠
```python
# 睡眠5秒
import time
time.sleep(5)
```





 