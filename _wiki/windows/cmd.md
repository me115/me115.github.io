---
layout: wiki
title:  windows 命令操作及快捷键
categories: windows
description: 
keywords: 
---

## 创建符号链接

```shell
$ln -sf file1 file2
# 其中file1是软件链接的名称,file2是实际文件的路径，以后通过file1就可以访问file2了
eg：
$mklink /D D:\me115.github.io\_wiki\images\wiki D:\me115.github.io\images\wiki 
$mklink /D D:\me115.github.io\_posts\images\posts D:\me115.github.io\images\posts
```
**注：以管理员权限打开命令行工具（c:\Windows\System32\cmd.exe）。**


