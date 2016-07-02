---
layout: wiki
title: Git commands
categories: Git
description: Git 常用命令
keywords: Git, 版本控制
---

# git常用命令

## 初始化仓库
- 新建仓库
 对现有的项目进行管理，进入该项目目录并输入

	
		git init

ps:该命令将创建.git目录，但不会主动将现有项目中的文件纳入管理（需要自行添加）；

- 克隆仓库
	  

		git clone https://github.com/libgit2/libgit2 

## 文件的四种状态
1. 未跟踪 untracked    
通过以下操作到达未跟踪状态：

		新建文件 vi aaa
		从索引区删除文件  git rm
2. 未修改 unmodified 
以下操作到达未修改状态：

		git commit
3. 已修改 modified  
到达已修改状态：

		 vi aaa
4. 已暂存 staged


		添加 git add

### 查看当前状态
- 当前状态   

		git status
- 状态简览 

		git status -s

PS: ‘MM’ 标记：左边M：修改并放入暂存区； 右边M：修改了还未放入暂存区

- 查看尚未暂存的差异

		git diff 
- 查看已暂存的将要添加到下次提交里的内容

		git diff --staged

## 三个工作区域
- Git仓库
- 工作目录
- 暂存区域

基本的 Git 工作流程

1. 在工作目录中修改文件。

	    vi aaa
2. 暂存文件，将文件的快照放入暂存区域。

	    git add .
3. 提交更新，找到暂存区域的文件，将快照永久性存储到 Git 仓库目录。

	    git commit -m "add file" aaa 
	已跟踪的文件修改，直接提交到库中：
    
      	git commit -a -m "update file aaa" aaa
	
## 移除文件
- 从暂存区中移除文件（硬盘上也删除）

	      git rm 
- 从暂存区中移除文件（硬盘上保留，即不再跟踪此文件）

	      git rm --cached README 
- 移动文件

	      git mv a b 

## 暂存区的操作
- 提交暂存

	      git commit -m "update what" 
- 补充提交

	      git commit --amend 
	最终只会显示成一个提交
- 取消暂存

	      git reset HEAD readme
将readme文件从暂存状态更改为未跟踪状态

- 回退到指定版本

	      git reset --hard :commit_hash_id


## 查看提交历史
- 查看日志

	      git log
- 显示最近两次提交的内容差异

	      git log -p -2 
- 单行显示

		$git log --pretty=oneline  
		$git log --oneline --decorate
		$git log --pretty=format:"%h - %an, %ar : %s"
          8029c4c - colin, 10 days ago : add redis_gallery_ad_impr
- 仅查看指定提交者的提交记录
		
		$ git log --committer=colin

	
## 远程仓库操作
- 查看远程仓库

		git remote -v 
- 更新本地
远程仓库数据拉取（不自动合并到当前工作目录）：
	
		git fetch origin 
		git merge origin/serverfix 将origin/serverfix合并到当前的分支
- 远程数据拉取并合并到当前目录：

		git pull origin
  自动到远程origin的跟踪分支上拉取并合并数据

- 推送到远程仓库

		git push origin master
		git push origin serverfix:serverfix 
推送本地的 serverfix 分支，将其作为远程仓库的 serverfix 分支

## 分支操作
### 创建分支
	git branch testing
### 分支切换
切换到已存在的分支上(HEAD 就指向这个分支)
      
      git checkout testing

### 新建并切换
基于当前目录新建一个分支并切换工作区到新分支上：

      git checkout -b iss53
新建一个基于远程仓库origin上的serverfix的分支：

      git fetch origin
      git checkout -b serverfix origin/serverfix
      or：
      git checkout --track origin/serverfix

### 跟踪分支
- 设置跟踪
设置已有的本地分支跟踪一个刚刚拉取下来的远程分支，或者想要修改正在跟踪的上游分支：

		git branch -u origin/serverfix

- 查询跟踪关系
查看设置的所有跟踪分支

		git branch -vv


### 删除分支
- 删除本地分支

		git branch -d hotfix
- 删除远程分支

		git push origin --delete serverfix

### 分支合并
- 合并merge
合并 iss53 分支到master 分支(ps：被合并的分支为当前工作区)
	
		git checkout master
		git merge iss53
- 变基rebase
变基是将一系列提交按照原有次序依次应用到另一分支上，而合并是把最终结果合在一起。

		git checkout experiment
		git rebase master


- git 拉取仓库的时候，合并远程的内容:

```shell
git pull --rebase
```

### 查看当前分支列表

	git branch

ref：《Pro Git 2.0》

Posted by: 大CC | 14MAY,2016
博客：[blog.me115.com](http://blog.me115.com) [[订阅](http://blog.me115.com/feed)]
Github：[大CC](https://github.com/me115)
