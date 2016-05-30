---
author: me115wp
comments: true
date: 2011-07-07 03:17:55+00:00
layout: post
link: http://blog.me115.com/2011/07/162
slug: google-reader-%e8%83%8c%e6%99%af%e8%87%aa%e5%ae%9a%e4%b9%89
title: Google Reader 背景自定义
wordpress_id: 162
categories:
- Beautiful Life
tags:
- gReader
---

黑字白底的google Reader读起来很费劲，时间长了，眼睛很容易疲劳。通过参考SEIN的文章，从而实现背景自定义实现；

 

效果如下（注：正文为本文效果，列表非也）：

 

[![image](http://blog/wp-content/uploads/2011/07/image_thumb.png)](http://blog/wp-content/uploads/2011/07/image1.png)

 

首先，需要安装stylish：[https://chrome.google.com/webstore/detail/fjnbnpbmkenffdnngjfgmeleoegfcffe](https://chrome.google.com/webstore/detail/fjnbnpbmkenffdnngjfgmeleoegfcffe)

 

然后，安装我提供的这个自定义脚本，从而实现背景自定义：[http://files.cnblogs.com/me115/GoogleReaderBackGrounder.user.js](http://files.cnblogs.com/me115/GoogleReaderBackGrounder.user.js)

 

这个自定义脚本非常简单，就是修改css：

 

entry .entry-body,.entry .entry-title,div.entry-body,div.entry-main,.item-body,div#nav

 

{background-color:#67b884 !important; }

 

如果对这个背景色不满意，自己想换可以直接修改后，再拖到chrome中安装；

 

如果想让列表更炫（如上图中的列表多彩效果，可以安装一个插件Webpage Decorator：[https://chrome.google.com/webstore/detail/idmfdjpebmchkoghokmjmgbeejhfjncc](https://chrome.google.com/webstore/detail/idmfdjpebmchkoghokmjmgbeejhfjncc)）

 

Over。

 

参考：[http://jandan.net/2008/09/26/stylish-google-reader.html](http://jandan.net/2008/09/26/stylish-google-reader.html)
