---
layout: page
title: About
description: 大CC的个人博客
keywords: 大CC
comments: true
menu: 关于
permalink: /about/
---

这里是大CC的技术博客，主要关注程序设计、阅读分享及个人管理。

希望在我的站点上，能发现对你有价值的内容；

如果你对这个站点感兴趣，欢迎订阅我的博客：http://blog.me115.com/feed


## 坚信

* 多点读书，总是有用的
* 时间用在哪，成绩就在那

## 联系

* GitHub：[@me115](https://github.com/me115)
* 博客：[{{ site.title }}]({{ site.url }})
* 豆瓣: [@大CC](http://www.douban.com/people/me115)

## Skill Keywords

#### Software Engineer Keywords
<div class="btn-inline">
    {% for keyword in site.skill_software_keywords %}
    <button class="btn btn-outline" type="button">{{ keyword }}</button>
    {% endfor %}
</div>

#### Mobile Developer Keywords
<div class="btn-inline">
    {% for keyword in site.skill_mobile_app_keywords %}
    <button class="btn btn-outline" type="button">{{ keyword }}</button>
    {% endfor %}
</div>

#### Windows Developer Keywords
<div class="btn-inline">
    {% for keyword in site.skill_windows_keywords %}
    <button class="btn btn-outline" type="button">{{ keyword }}</button>
    {% endfor %}
</div>
