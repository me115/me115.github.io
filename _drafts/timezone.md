django timezone 设置



## 在settings.py 中设置时区

```python
TIME_ZONE = 'Asia/Shanghai'
```

## 获取当前的时间

``` 
import datetime
dt1 = datetime.datetime.now() # current time
print 'date1:', str(dt1)

from django.utils import timezone
dt2 = timezone.now()
print 'date2:', str(dt2)
dt3 = timezone.localtime(timezone.now())
print 'date2:', str(dt3)

output：
date1: 2016-11-21 18:09:38.575713
date2: 2016-11-21 10:09:38.575772+00:00
date2: 2016-11-21 18:09:38.575792+08:00

```

通过datetime.datetime.now() 拿到的时间不带时区信息，显示的就是当前的系统时间；

通过timezone.now() 获取到的utc时间， 要显示的需要转换到当前时区时间（通过timezone.localtime 转换）

## MYSQL中 的时区

mysql使用的是系统时区，系统默认配置的是东八区

```
mysql -h 10.4.21.169 -P1234 -umyname -p mypwd
mysql> SELECT @@global.time_zone, @@session.time_zone;
+--------------------+---------------------+
| @@global.time_zone | @@session.time_zone |
+--------------------+---------------------+
| SYSTEM             | SYSTEM              |
+--------------------+---------------------+
1 row in set (0.00 sec)
```


django存放到DB中的时间显示的为UTC时间，比如上面三种方式的获取到的时间存到DB中，从DBshell中显示的都是UTC时间：
```
date1: 2016-11-21 18:13:16.036195
DB shows： 2016-11-21 10:13:16.036195

date2: 2016-11-21 10:18:17.155390+00:00
DB shows: 2016-11-21 10:18:17.155390

date3: 2016-11-21 18:17:15.679798+08:00
DB shows：2016-11-21 10:17:15.686597
```


从DB中取出来的时间都是UTC时间，要展示则使用timezone.localtime转换为当前时区时间；





