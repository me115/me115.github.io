## Xshell 快捷键

删除
ctrl + d      删除光标所在位置上的字符相当于VIM里x或者dl
ctrl + h      删除光标所在位置前的字符相当于VIM里hx或者dh
ctrl + k      删除光标后面所有字符相当于VIM里d shift+$
ctrl + u      删除光标前面所有字符相当于VIM里d shift+^
ctrl + w      删除光标前一个单词相当于VIM里db
ctrl + y      恢复ctrl+u上次执行时删除的字符
ctrl + ?      撤消前一次输入
alt  + r      撤消前一次动作
alt  + d     删除光标所在位置的后单词

移动
ctrl + a      将光标移动到命令行开头相当于VIM里shift+^
ctrl + e      将光标移动到命令行结尾处相当于VIM里shift+$
ctrl + f      光标向后移动一个字符相当于VIM里l
ctrl + b      光标向前移动一个字符相当于VIM里h
ctrl + 方向键左键    光标移动到前一个单词开头
ctrl + 方向键右键    光标移动到后一个单词结尾
ctrl + x       在上次光标所在字符和当前光标所在字符之间跳转
alt  + f      跳到光标所在位置单词尾部


替换
ctrl + t       将光标当前字符与前面一个字符替换
alt  + t     交换两个光标当前所处位置单词和光标前一个单词
alt  + u     把光标当前位置单词变为大写
alt  + l      把光标当前位置单词变为小写
alt  + c      把光标当前位置单词头一个字母变为大写
^oldstr^newstr    替换前一次命令中字符串   

历史命令编辑
ctrl + p   返回上一次输入命令字符
ctrl + r       输入单词搜索历史命令
alt  + p     输入字符查找与字符相接近的历史命令
alt  + >     返回上一次执行命令

其它
ctrl + s      锁住终端
ctrl + q      解锁终端
ctrl + l        清屏相当于命令clear
ctrl + c       另起一行
ctrl + i       类似TAB健补全功能
ctrl + o      重复执行命令
alt  + 数字键  操作的次数

------------------------------------------------------------------------------
Ctrl + a 可以快速切换到命令行开始处
Ctrl + e 切换到命令行末尾
Ctrl + r 在历史命令中查找
Ctrl + u 删除光标所在位置之前的所有字符
Ctrl + k 删除光标所在位置之后的所有字符
ctrl + w 删除光标之前的一个单词
Ctrl + d 结束当前输入、退出shell
ctrl + s 可用来停留在当前屏 ctrl + q 恢复刷屏
ctrl + l 清屏

CTRL 键相关的快捷键:

Ctrl + a - Jump to the start of the line
Ctrl + b - Move back a char
Ctrl + c - Terminate the command  //用的最多了吧?
Ctrl + d - Delete from under the cursor
Ctrl + e - Jump to the end of the line
Ctrl + f - Move forward a char
Ctrl + k - Delete to EOL
Ctrl + l - Clear the screen  //清屏，类似 clear 命令
Ctrl + r - Search the history backwards  //查找历史命令
Ctrl + R - Search the history backwards with multi occurrence
Ctrl + u - Delete backward from cursor // 密码输入错误的时候比较有用
Ctrl + xx - Move between EOL and current cursor position
Ctrl + x @ - Show possible hostname completions 
Ctrl + z - Suspend/ Stop the command
补充:
Ctrl + h - 删除当前字符
Ctrl + w - 删除最后输入的单词 

ALT 键相关的快捷键:

平时很少用。有些和远程登陆工具冲突。

Alt + < - Move to the first line in the history
Alt + > - Move to the last line in the history
Alt + ? - Show current completion list
Alt + * - Insert all possible completions
Alt + / - Attempt to complete filename
Alt + . - Yank last argument to previous command
Alt + b - Move backward
Alt + c - Capitalize the word
Alt + d - Delete word
Alt + f - Move forward
Alt + l - Make word lowercase
Alt + n - Search the history forwards non-incremental
Alt + p - Search the history backwards non-incremental
Alt + r - Recall command
Alt + t - Move words around
Alt + u - Make word uppercase
Alt + back-space - Delete backward from cursor 
// SecureCRT 如果没有配置好，这个就很管用了。

其他特定的键绑定:

输入 bind -P 可以查看所有的键盘绑定。这一系列我觉得更为实用。

Here "2T" means Press TAB twice
$ 2T - All available commands(common) //命令行补全，我认为是 Bash 最好用的一点 
$ (string)2T - All available commands starting with (string)
$ /2T - Entire directory structure including Hidden one
$ ./2T - Only Sub Dirs inside including Hidden one
$ *2T - Only Sub Dirs inside without Hidden one
$ ~2T - All Present Users on system from "/etc/passwd" //第一次见到，很好用
$ $2T - All Sys variables //写Shell脚本的时候很实用
$ @2T - Entries from "/etc/hosts"  //第一次见到
$ =2T - Output like ls or dir //好像还不如 ls 快捷
补充:
Esc + T - 交换光标前面的两个单词
表2-