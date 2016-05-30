---
author: me115wp
comments: true
date: 2011-10-03 08:38:44+00:00
layout: post
link: http://blog.me115.com/2011/10/165
slug: python%e9%80%9a%e8%bf%87elementtree%e6%9d%a5%e6%93%8d%e4%bd%9cxml
title: '[python]通过ElementTree来操作XML'
wordpress_id: 165
categories:
- Python
tags:
- Python
---

本文讲解如何通过ElementTree来操作XML；

 

**1.引入库**           
需要用到3个类，ElementTree，Element以及建立子类的包装类SubElement   
from xml.etree.ElementTree import ElementTree           
from xml.etree.ElementTree import Element           
from xml.etree.ElementTree import SubElement as SE

 

    
**2.读入并解析**           
tree = ElementTree(file=xmlfile)           
root = tree.getroot()           
读入后，tree是ElementTree的类型，获取xml根结点使用getroot()方法；           


 

XML示例文件：        
<item sid='1712' name = '大CC' >         
<a id=1></a>         
<a id=2></a>         
</item>

 

    
**3.获取儿子结点**           
查找Element的所有子结点:           
AArry = item.findall('a')           
也可使用getchildren()：           
childs = item.getchildren()           
for subItem in childs:           
print subItem.get('id')

    

        
**4.插入儿子结点**           
方法一：           
item = Element("item", {'sid' : '1713', 'name' : 'ityouhui'})           
root.append(item)           
方法二：           
SE(root,'item',{'sid':'1713','name':'ityouhui'})           
法一的好处是插入之后可以对item继续操作。法二是写法上简单，其中SE就是SubElement,在引入处做了声明；

     

        
**5.操作属性**           
获取Element的某个属性值（eg：获取item的 name）           
print root.find('item/name').text           
print item.get('name')           
获取Element所有属性           
print item.items() # [('sid', '1712'), ('name', '大CC')]           
print item.attrib # {'sid': '1712', 'name': '大CC'}

     

        
**6.美化XML**           
在写入之前，传入root调用此函数，写入的XML文件格式整齐美观：           
indent(root)           
book.write(xmlfile,'utf-8')

     

    
    
    ## Get pretty look
    def indent( elem, level=0):
        i = "n" + level*"  "
        if len(elem):
            if not elem.text or not elem.text.strip():
                elem.text = i + "  "
            for e in elem:
                indent(e, level+1)
            if not e.tail or not e.tail.strip():
                e.tail = i
        if level and (not elem.tail or not elem.tail.strip()):
            elem.tail = i
        return elem
