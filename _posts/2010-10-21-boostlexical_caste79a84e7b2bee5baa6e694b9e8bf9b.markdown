---
author: me115wp
comments: true
date: 2010-10-21 04:14:21+00:00
layout: post
link: http://blog.me115.com/2010/10/29
slug: boostlexical_cast%e7%9a%84%e7%b2%be%e5%ba%a6%e6%94%b9%e8%bf%9b
title: Boost::Lexical_cast的精度改进
wordpress_id: 29
categories:
- C++编程
tags:
- boost
---

 

Lexical_cast函数模版为以文本表示的任意的类型之间的转换提供了方便和一致的形式。它提供的简化形式是在表达式级别上的简单性易用性。

 

现象：

 
    
    	string s = "123456.78932323";
    	float f = boost::lexical_cast<float>(s);//转过后的结果是123456.79
    	double d_f  = boost::lexical_cast<float>(s);//转过后的结果是123456.7890656
    
    	double d  = boost::lexical_cast<double>(s);//123456.78932323000 --总共为17位





早起的版本对于Lexical_cast的精度默认值总是设置为6；





而最近的1.4版本，查看源码，发现其已根据所转换的类型的默认精度来设置其转换精度：




    
    template<typename InputStreamable>
    bool operator>>(InputStreamable& output)
    {
    
    lcast_set_precision(stream, static_cast<InputStreamable*>(0));
    
    ｝
    
    inline void lcast_set_precision(std::ios_base& stream, T*)
    {
        stream.precision(lcast_get_precision<T>());
    }





float为：9





double：17：





string类型的为：118





windows平台





如果需要精确的转换精度，目前lexical_cast没有提供精度设置接口，可以自己写一个加上，不过少了可移植性，boost官方文档上给出说明：





对于更多相关的转换，比如比lexical_cast提供的默认行为更为精确的或者需要更严密的格式转换控制，推荐传统的stringstream。




    
    	double dble = 123456.7893232338;
    	std::stringstream  ss;
    	ss.precision(20);
    	ss << setw(15) << dble;
    	string ssd = ss.str(); //转过后的结果:123456.7893232338
