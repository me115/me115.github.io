---
layout: wiki
title: Python
categories: Python
description: django 跨域访问 json数据
keywords: Python
---

# django 跨域访问 json数据
跨域访问api，获取json 格式数据；需要将对json 数据的请求转换为jsonp的请求；

## 前端
```js
function ajaxServiceGetJsonP(url, completeFunc) {
    var resp;

    var jqXhr = jQuery.ajax ({
        url: url,
        type: 'GET',
        dataType: 'jsonp', // 数据类型
        jsonp: "callback",  //传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(默认为:callback)
        success: function(data, textStatus, xhr){
            resp = data;
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    }).complete(function() {
        completeFunc(resp)
    });

    return jqXhr;
}
```

使用 `$.getJSON(url,[data],[callback])` 方法也是支持jsonp：
可以使用$.getJSON(url,[data],[callback])方法(详细可以参考http://api.jquery.com/jQuery.getJSON/)。改用jQuery的getJSON方法来实现(下面的例子没用用到向服务传参，所以只写了getJSON(url,[callback]))

```js
<script type="text/javascript">
    $.getJSON("http://localhost:20002/MyService.ashx?callback=?",function(data){
        alert(data.name + " is a a" + data.sex);
    });
</script>
```

## 后端
之前为json格式的返回：

```python
return HttpResponse(jsonp_format(callback, response), content_type='application/json')
```

修改为jsonp：
```python
callback = request.GET.get('callback')
json_format = "%s(%s);" % (callback, json.dumps(response))
return HttpResponse(json_format, content_type='application/json')
```

完成跨域请求；

## extra: url解析支持跨域的解析
使用django的template编写的页面逻辑中，需要访问的动态url，地址通过url revert规则生成：

```python
<a href="{ url 'caching:update_instance' instance.id }">更新信息</a>

```

通过url revert 解析的都是本域名内的地址；
如何针对不同场景解析出不同域名？
使用templatetags；

### 定义slave_urls.py

```python
from django import template
from django.conf import settings

register = template.Library()

SLAVE_DOMAIN = '' if settings.DEBUG else 'http://slave.cache.demo.org'

SLAVE_URLS = {
    'manage:control': '/manage/control/',
    'metrics:info': '/api/metrics/{0}/',
}

@register.simple_tag
def slave_url(shortcut, *args):
    if shortcut not in SLAVE_URLS:
        raise Exception('Cannot found valid url for shortcut: ' + shortcut)
    return SLAVE_DOMAIN + SLAVE_URLS[shortcut].format(*args)
```
该文件放到任何一个module app下的 templatetags文件夹下就可被django 检查到；

### 前端模版页面
```python
{ extends 'base.html' }
{ load staticfiles }
{ load slave_urls }
{ load extras }

# 调用
<a href="{ slave_url 'metrics:info' instance.id }">更新信息</a>
```

## ref

json和jsonp
http://www.cnblogs.com/dowinning/archive/2012/04/19/json-jsonp-jquery.html
