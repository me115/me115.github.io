---
author: me115wp
comments: true
date: 2013-01-23 14:16:41+00:00
layout: post
link: http://blog.me115.com/2013/01/246
slug: yii-%e7%9a%84%e7%bc%93%e5%ad%98%ef%bc%88%e9%a1%b5%e9%9d%a2%e7%bc%93%e5%ad%98%e9%85%8d%e7%bd%ae%e5%ae%9e%e4%be%8b%ef%bc%89
title: Yii 的缓存（页面缓存配置实例）
wordpress_id: 246
categories:
- WEB开发
---

作为PHP的开源框架，自然少不了对缓存的支持。Yii缓存可以在不同的级别使用。在最低级别，可用来缓存单个数据(数据缓存）。往上一级，我们缓存一个由视图脚本生成的页面片断（片段缓存）。在最高级别，可存储整个页面以便需要的时候直接从缓存读取。本文说明页面缓存的配置及实现效果；

实现分为2步；

**1. 在config文件加入缓存组件.**

'cache' => array (

'class' => 'system.caching.CFileCache',

'directoryLevel' => 2,

),

class标识需要使用的缓存媒介，用途比较广的类型基本都有支持：

CMemCache: 使用 PHP memcache 扩展.

CApcCache: 使用 PHP APC 扩展.

CDbCache: 使用一张数据库表来存储缓存数据。

CFileCache: 使用文件来存储缓存数据。 特别适用于大块数据(例如页面)。

当然，yii也可以支持Redis，需要装一个插件：

[http://www.yiibase.com/download/view/32.html](http://www.yiibase.com/download/view/32.html)

本文实例使用的是文件缓存，对于文件缓存，缓存到的位置为protected/runtime/；directoryLevel设置缓存文件的目录深度；如果缓存页面特别多，这个值需要设置大点，否则每个目录下的页面会很多；

**2. 在要做缓存的控制器里定义过滤器。**

public function filters() {

return array (

array (

'COutputCache + post, list',

'duration' => 3600,

'varyByParam' => array('id','page'),

'dependency' => array(

'class'=>'CDbCacheDependency',

'sql'=>'SELECT MAX(id) FROM me115_book',

)

);

}

COutputCache 是用于处理缓存的类，如果只填'COutputCache'，则控制器里所有action都会通过缓存过滤，定义'COutputCache + post, list'，表示只对以下方法进行缓存：actionPost, actionList

duration 是缓存的时间，单位是秒,

varyByParam 是指定一系列GET参数名称列表, 使用相应的值去确定缓存内容的版本，即同一个action用于区分是不同页面的的参数，此处我以id和page来区分不同页面。

除varyByParam以外，还可以采用其他的条件来区分页面：

varyByExpression：指定缓存内容通过自定义的PHP表达式的结果而变化

varyByRoute：指定缓存内容基于请求的路由不同而变化 (controller 和 action)

varyBySession：指定是否缓存内容. 因用户session不同而变化

dependency'指定缓存失效依赖关系：可指定文件或数据库；本文采用的是数据库依赖CDbCacheDependency；

本例指定的是数据库，通过数据表的某个值的变化来确定缓存是否失效。例如，如果在表中新增了一条me115_book记录，即使缓存才过了2分钟（<3600)，仍然判断为失效，从而查询数据库，生成整个页面，再次缓存；

**检查：**

查看当前页面是否缓存，可以dump输出一个当前服务器时间，从而检查当前页面是否已缓存；

**优化效果：**

优化站点为一个博客站点([me115.com](http://www.me115.com)),除了DNS解析转接外，未进行任何优化，优化前的数据为：

![wps_clip_image-3046](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image3046.png)

![wps_clip_image-10360](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image10360.png)

首字节时间为842ms；

采用页面缓存之后的效果：

[![wps_clip_image-29476](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image29476_thumb.png)](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image294761.png)

[![wps_clip_image-21544](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image21544_thumb.png)](http://blog.me115.com/wp-content/uploads/2013/01/wps_clip_image215441.png)

首字节时间为376ms；html生成的时间大大缩短，后台时间减少了一倍。

当然，通过本图可以看到整个站点的用时还是比较长，主要是在页面组件（css/js/图片）上的下载耗费了不少时间，后续将针对这方面进行前端优化；

**Posted by: 大CC | DEC17,2012**

博客：[blog.me115.com](http://blog.me115.com) **[**[**订阅**](http://feed.feedsky.com/me115)**] **

微博：[新浪微博](http://weibo.com/bigcc115)

****

**进一步参考：**

[1] http://www.yiiframework.com/doc/api/1.1/COutputCache#dependency-detail

[2] [http://www.yiiframework.com/forum/index.php?/topic/3592-%E5%85%B3%E4%BA%8E%E9%A1%B5%E9%9D%A2%E7%BC%93%E5%AD%98%E7%9A%84%E7%96%91%E9%97%AE/page__p__19429](http://www.yiiframework.com/forum/index.php?/topic/3592-关于页面缓存的疑问/page__p__19429)

[3] [http://www.yiibase.com/download/view/32.html](http://www.yiibase.com/download/view/32.html)
