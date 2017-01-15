---
layout: wiki
title: python 类和对象
categories: [python]
description: some word here
keywords: keyword1, keyword2
---

## 成员变量和类变量

python的变量不用初始化，在成员变量使用地方直接赋值后就可以使用；当这样会带来一个风险；如果没有调用赋值函数而直接调用输出，就会抛出异常；所以，良好的编程习惯是给类变量和成员变量一个初始化的值；

### 类变量/静态变量

类变量即静态变量，作用域为类；可通过类名直接访问

```python
class Student(object):
    scholl = 'tingshua' # 可省略，后续只要赋值函数在打印函数之前，语法就没问题

    def print_scholl(self):
    	print Student.scholl
    def set_scholl(self, value):
    	Student.scholl = value

if __name__ == '__main__':
    a = Student('A')
    b = Student('B')
    b.print_scholl()
    b.set_scholl('beijing')
    a.print_scholl()
    b.print_scholl()
    print Student.scholl # 无实例直接访问
    
output:
tingshua
beijing
beijing
beijing
```

### 成员变量

```python
class Student(object):
    def __init__(self, value):
        self.name = value # 同样，可不初始化；

    def set_name(self, value):
        self.name = value
        
    def print_name(self):
        print self.name
        

if __name__ == '__main__':
    a = Student('A')
    b = Student('B')
    a.print_name()
    b.set_name('BBB')
    b.print_name()
    
output：
A
BBB
```

## 类方法和静态方法

**静态方法：**无法访问类属性、实例属性，相当于一个相对独立的方法，跟类其实没什么关系，换个角度来讲，其实就是放在一个类的作用域里的函数而已。

**类成员方法：**可以访问类属性，无法访问实例属性。

```python
class Student(object):
    # age = 24
    scholl = 'tingshua'

    def __init__(self, value):
        self.name = value
        
    @staticmethod
    def print_info():
        print 'welcome to scholl:%s' % Student.scholl # 这里使用了一个迂回的方式访问到了类变量，但这样看着比较别扭，有这种需求，还是直接使用类方法；

    @classmethod
    def print_scholl(cls):
        print 'welcome to school:%s' % cls.scholl

if __name__ == '__main__':
    # a = Student('A')
    # b = Student('B')
    Student.print_info()
    Student.print_scholl()
```



## 对象的继承

```python
class People(object):
    def __init__(self, sex):
        self.sex = sex

    def print_sex(self):
        print self.sex


class Student(People):
    def __init__(self, sex, value):
        People.__init__(self, sex)  # must include 'self' at first
        self.name = value


if __name__ == '__main__':
    a = Student('boy', 'liming')
    a.print_sex()

output：
boy
```


