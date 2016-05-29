---
author: me115wp
comments: true
date: 2011-05-04 09:33:59+00:00
layout: post
link: http://blog.me115.com/2011/05/153
slug: cppunit-%e4%bd%bf%e7%94%a8%e6%8c%87%e5%8d%97unix
title: CPPUnit 使用指南[Unix]
wordpress_id: 153
categories:
- C++编程
- Linux&amp;Unix
tags:
- cppunit
---

单元测试工具CPPUnit在Windows平台下使用图形界面，操作非常直观；但在Unix平台下，就需要花点功夫配置一番：

 

1.下载文件：

 

本人使用的是[cppunit-1.12.0.rar](http://u.115.com/file/f6a03bec8) 版本，可以在以下地址下载（[http://u.115.com/file/f6a03bec8](http://u.115.com/file/f6a03bec8)）共享有效期有一个月，这软件在网上也好找。如以上地址过期，未找到合适版本，请留言联系；

 

2.不用安装，直接将cppunit解压到指定路径；

 

3.编写CPPUnit makefile, 需指定以下3中路径：

 

1.待测试代码路径；

 

2.CPPUNIT软件路径；

 

3.测试代码路径；

 

给出makefile范例：

 
    
    CC      =      CC
    MV      =       mv
    CP      =       cp
    RM      =       rm
    CFLAGS  =
    
    # Change It
    COLIN_HOME =/userhome/colin
    <strong>CPPUNIT_HOME</strong> = /userhome/colin/tools/cppunit-1.12.0
    <strong>PROJECT_HOME</strong> = $(COLIN_HOME)/cl/src
    <strong>UNITTEST_HOME</strong>= $(COLIN_HOME)/cl/unittest
    
    ORALIB = -L$(ORACLE_HOME)/lib32 -lclntsh
    CPPUNITLIB = -L$(CPPUNIT_HOME)/lib -lcppunit
    ORAINC = -I$(ORACLE_HOME)/rdbms/demo -I$(ORACLE_HOME)/rdbms/public -I$(ORACLE_HOME)/precomp/public
    CXXFLAGS = -O2 -g #-Wall
    
    APPINC = -I$(PROJECT_HOME)
             -I$(CPPUNIT_HOME)/include 
             -I$(UNITTEST_HOME)
    
    clOBJ=$(PROJECT_HOME)/common/Record.o 
                  $(PROJECT_HOME)/common/Recordset.o 
                  $(PROJECT_HOME)/common/DBHandler.o 
                  $(PROJECT_HOME)/common/DBHandlerImpl.o 
                  $(PROJECT_HOME)/common/LogMacros.o 
                  $(PROJECT_HOME)/common/DateTime.o 
                  $(PROJECT_HOME)/clTypeB/clTypeBHandler.o 
                  $(PROJECT_HOME)/clTypeB/clTypeBUpdate.o
    
    
    clTESTOBJ =  $(UNITTEST_HOME)/clTypeBTest.o 
                 $(UNITTEST_HOME)/clTypeBUnitTest.o
    
    OBJS = $(clOBJ) $(clTESTOBJ)
    
    all: cltest
    
    cltest: $(OBJS)
            $(CC) -o $@  $(OBJS) $(CXXFLAGS) $(ORALIB) $(CPPUNITLIB)
    
    .SUFFIXES : .cpp
    .cpp.o :
            $(CC) $(CFLAGS) $(APPINC) $(ORAINC) -c -O $< -o $*.o    
    
    .PHONY:clean
    clean:
            $(RM) $(OBJS)









4.在$(COLIN_HOME)/cl/unittest/编写测试代码，测试代码由两部分组成：





1.带主函数的文件，固定格式，不用修改：




    
    #include <cppunit/extensions/HelperMacros.h>
    #include <cppunit/CompilerOutputter.h>
    #include <cppunit/extensions/TestFactoryRegistry.h>
    #include <cppunit/ui/text/TestRunner.h>
    
    int main(int argc, char* argv[])
    {
        // Get the top level suite from the registry
        CppUnit::Test *suite = CppUnit::TestFactoryRegistry::getRegistry().makeTest();
    
        // Adds the test to the list of test to run
        CppUnit::TextUi::TestRunner runner;
        runner.addTest( suite );
    
        // Change the default outputter to a compiler error format outputter
        runner.setOutputter( new CppUnit::CompilerOutputter( &runner.result(),
            std::cerr ) );
        // Run the tests.
        bool wasSucessful = runner.run();
    
        // Return error code 1 if the one of test failed.
        return wasSucessful ? 0 : 1;
    }





2.单元测试类ClassSvrPluginTest：其中加入了一些宏，类似于MFC处理方式，按照例子相应的加入：





对于每个测试用例，可写成一个函数test1(),并加入到宏CPPUNIT_TEST（）中：





ClassSvrPluginTest.h




    
    #pragma once
    #include <cppunit/extensions/HelperMacros.h>
    using namespace std;
    
    class ClassSvrPluginTest :public CppUnit::TestFixture
    {
        CPPUNIT_TEST_SUITE(ClassSvrPluginTest);
         CPPUNIT_TEST(test1);
         CPPUNIT_TEST(test2);
        CPPUNIT_TEST_SUITE_END();
    public:
        ClassSvrPluginTest(void);
        ~ClassSvrPluginTest(void);
        void test1();
        void test2();
    };





ClassSvrPluginTest.cpp




    
    #include "ClassSvrPluginTest.h"
    
    CPPUNIT_TEST_SUITE_REGISTRATION(ClassSvrPluginTest);
    
    ClassSvrPluginTest::ClassSvrPluginTest(void)
    {
    }
    
    ClassSvrPluginTest::~ClassSvrPluginTest(void)
    {
    }
    
    void ClassSvrPluginTest::test1()
    {
        int i = -1;
        CPPUNIT_ASSERT_EQUAL(-1, i);
    }
    
    void ClassSvrPluginTest::test2()
    {
        int i = -1;
        CPPUNIT_ASSERT_EQUAL(-1, i);
    }





配置完毕，makefile生成文件可执行文件cltest即可进行测试。





当然，上述代码并没有实际的测试源代码，可以ClassSvrPluginTest.cpp中包含源代码的头，然后，生成对象，对其函数的返回值进行测试，cppunit 通过宏CPPUNIT_ASSERT_EQUAL(-1, i)来判断测试结果是否和预期相同。





over！
