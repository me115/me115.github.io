=== 七牛镜像存储 WordPress 插件 ===
Contributors: denishua
Donate link: https://me.alipay.com/denishua
Tags: CDN
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

七牛镜像存储 WordPress 插件：一键实现 WordPress 博客静态文件 CDN 加速。

== Description ==

七牛支持传统 CDN 的镜像存储，对于很多 WordPress 站点来说，有了这个功能，就无需将原来的图片上传到七牛的服务器上，只需在 WordPress 站点做些简单的修改，就可以使用七牛的 CDN 服务了。

详细介绍： http://blog.wpjam.com/project/wpjam-qiniutek/


== Installation ==

1. 上传 `wpjam-qiniutek`目录 到 `/wp-content/plugins/` 目录
2. 在后台插件菜单激活该插件
3. 在后台设置你在七牛绑定的域名即可

== Screenshots ==

1. 七牛镜像存储设置
2. 更新文件

== Changelog ==

= 0.8 =
新增后台设置本地静态文件域名
新增后台设置jQuery版本

= 0.7 =
新增后台设置缓存目录
新增后台日志列表显示缩略图

= 0.61 =
更新七牛 SDK

= 0.6 =
新增上传 Robots.txt 功能，防止搜索引擎索引。
支持替换 Feed 中的图片地址。
新增 wpjam_post_thumbnail 函数，使用七牛缩略图功能代替 WP 自带的缩略图功能，并且能够自动生成更多类型的缩略图。

= 0.5 = 
添加静态文件扩展名选项，用户可以按照自己的需求自定义静态文件扩展名。

= 0.43 =
* 修正 wpjam_qiniutek_enqueue_scripts 函数冲突问题。

= 0.42 = 
* 修正后台图标地址

= 0.41 = 
* bug 修正

= 0.4 = 
* 修正匹配正则
* 将 WP 默认的 jQuery 库替换成七牛的开源库项目 staticfile.org 上面的库
* 增加 七牛 SDK 判断，避免和其它使用七牛 SDK 的插件发生冲突。

= 0.3 =
* 支持在 WordPress 后台更新文件。

= 0.2 =
* 支持所有静态文件镜像到七牛

= 0.1 =
* 初始版本