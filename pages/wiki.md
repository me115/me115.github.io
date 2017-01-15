---
layout: page
title: Wiki
description: 人越学越觉得自己无知
keywords: 维基, Wiki
comments: false
menu: 维基
permalink: /wiki/
---

> 记多少快捷键会让脑袋爆炸呢？

## Python
<ul class="listing">
{% for wiki in site.wiki %}

{% if wiki.categories contains  'Python' or wiki.categories contains  'python' %}
<li class="listing-item"><a href="{{ wiki.url }}">{{ wiki.title }}</a></li>
{% endif %}
{% endfor %}
</ul>

## Tools
<ul class="listing">
{% for wiki in site.wiki %}
{% if wiki.categories contains  "tools" %}
<li class="listing-item"><a href="{{ wiki.url }}">{{ wiki.title }}</a></li>
{% endif %}
{% endfor %}
</ul>

## all of them
<ul class="listing">
{% for wiki in site.wiki %}
{% if wiki.title != "Wiki Template" %}
<li class="listing-item"><a href="{{ wiki.url }}">{{ wiki.title }}</a></li>
{% endif %}
{% endfor %}
</ul>
