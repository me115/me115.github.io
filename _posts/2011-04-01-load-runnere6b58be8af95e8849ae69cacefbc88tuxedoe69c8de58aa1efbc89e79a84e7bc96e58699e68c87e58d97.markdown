---
author: me115wp
comments: true
date: 2011-04-01 03:13:52+00:00
layout: post
link: http://blog.me115.com/2011/04/140
slug: load-runner%e6%b5%8b%e8%af%95%e8%84%9a%e6%9c%ac%ef%bc%88tuxedo%e6%9c%8d%e5%8a%a1%ef%bc%89%e7%9a%84%e7%bc%96%e5%86%99%e6%8c%87%e5%8d%97
title: Load Runner测试脚本（tuxedo服务）的编写指南
wordpress_id: 140
categories:
- C++编程
tags:
- LR测试
- Tuxedo
---

**1.熟悉loadrunner与c++中调用tuxedo服务的对应API：**

 

c++：

 

对比表     <table cellpadding="0" border="1" cellspacing="0" ><tbody >       <tr >         
<td width="179" valign="top" >
</td>          
<td width="121" valign="top" >           

C++中

        
</td>          
<td width="212" valign="top" >           

loadrunner

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

分配内存

        
</td>          
<td width="121" valign="top" >           

tpalloc（）

        
</td>          
<td width="212" valign="top" >           

lrt_tpalloc（）

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

释放内存

        
</td>          
<td width="121" valign="top" >           

tpfree（）

        
</td>          
<td width="212" valign="top" >           

lrt_tpfree（）

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

定义发送接收缓冲区

        
</td>          
<td width="121" valign="top" >           

FBFR32* 

        
</td>          
<td width="212" valign="top" >           

FBFR32* 

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

缓冲区初始化

        
</td>          
<td width="121" valign="top" >           

Finit32（）

        
</td>          
<td width="212" valign="top" >           

lrt_Finitialize32（）

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

向缓冲区中加入变量

        
</td>          
<td width="121" valign="top" >           

Fadd32（）

        
</td>          
<td width="212" valign="top" >           

lrt_Fadd32_fld（）

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

获取缓冲区中变量

        
</td>          
<td width="121" valign="top" >           

Fget32（）

        
</td>          
<td width="212" valign="top" >           

lrt_save32_fld_val（）

        
</td>       </tr>        <tr >         
<td width="179" valign="top" >           

call tuxedo服务

        
</td>          
<td width="121" valign="top" >           

tpcall（）

        
</td>          
<td width="212" valign="top" >           

lrt_tpcall（）

        
</td>       </tr>     </tbody></table>

 

主要用到就是以上几个函数，此外，为了统计与tuxedo交互期间的性能，应将事务统计插入到tacall前后：

 

lr_start_transaction（）

 

lrt_tpcall()

 

lr_end_transaction()

 

 

**2.注意事项**

 

需要注意的是，lr中相关的函数参数与c++中并不相同，不能想当然，一定要遵循其语法规则：

 

**a.变量申明**

 

lr中的变量申明都放在replay.vdf中；

 

**b.传参**

 

C++中：

 

#define FCCKEY ((FLDID32)167776166)

 

Fadd32(sndBuf,FCCKEY,(char*)key,(FLDLEN32)0)

 

LR中：

 

lrt_Fadd32_fld(sndBuf,"id=167776166",(char*)key,LRT_END_OF_PARMS);

 

对于标识的传入，需加上"id="；

 

同样，key的传入，也需加入value：

 

char key[30];

 

strcpy(key,"value=HELLOCOLIN^")；

 

**c.取参**

 

c++中：

 

int resind;

 

Fget32((FBFR32*)rcvBuf, F_RESULT_IND, oc, (char*)&resind, &maxlen);

 

cout << resind;

 

LR中：

 

int resind:

 

lrt_save32_fld_val((FBFR32*)rcvBuf,"id=5032",0,"resind");

 

lr_output_message("返回值：º%d",resind);

 

对比，可看出，在LR中，是以字符串形式传入参数的名字，其内部根据参数名来查找并匹配类型，再相应的填充数据；

 

熟悉以上几点后，编写tuxedo的LR测试脚本基本上就没什么问题了。

 

**附上一段LR脚本，供参考：**

 

//replay.vdf-----------------------------------------

 
    
    #ifndef TUXVDF_H
    #define TUXVDF_H
    
    #define	FCCKEY	((FLDID32)167776166)
    FBFR32* sndBuf;
    //char* sndBuf;
    FBFR32* rcvBuf;
    char* data_2;
    char* data_3;
    char key[61];
    char dataResult[513];
    int len;
    int resind;
    FLDOCC oc;





//Action.c-------------------------------------------------




    
    #include "lrt.h"
    #include "replay.vdf"
    
    Action()
    {
    	lrt_abort_on_error();
    
    	//sndBuf = lrt_tpalloc("STRING", "", 512);
    	len = 65000;
    
    	strcpy(key,"value=HelloColin^");
    
    	lrt_Finitialize32(sndBuf);
    
    	lrt_Fadd32_fld(sndBuf,"id=167776166",(char*)key,LRT_END_OF_PARMS);
    
    	/* Request STRING buffer 1 */
    	//lrt_strcpy(sndBuf, sbuf_1);
    	//rcvBuf = lrt_tpalloc("STRING", "", 10000000);
    	lr_start_transaction("ColinLau");
    	tpresult_int = lrt_tpcall("CC_Q_1",
    		(char*)sndBuf,
    		0,
    		(char**)&rcvBuf,
    		&olen,
    		0);
    	/* Reply STRING buffer 1 */
    	if(tpresult_int == -1){
    		lr_end_transaction("ColinLau", LR_FAIL);
    		lr_output_message("TpQuery:tpcall error");
    		return 0;
    	}
        lr_end_transaction("ClassCache", LR_PASS);
    
    	lrt_save32_fld_val((FBFR32*)rcvBuf,"id=5032",0,"resind");
    	lr_output_message("结果状态：%d",resind);
    	
    	lrt_save32_fld_val((FBFR32*)rcvBuf,"id=167776167",0,"dataResult") ;
    
        lr_output_message("返回值：",lr_eval_string("{dataResult}") ) ;//("{dataResult}")
    	lrt_abort_on_error();
    	
    	return 0;
    }





**注：在代码编写过程中，需要将内存的申请和释放放在vuser_init和vuser_end中，否则对测试结果影响非常大！**





vuser_init---------------




    
    #include "lrt.h"
    #include "replay.vdf"
    
    vuser_init()
    {
    	lrt_set_env_list(env_allow_array);
    	lrt_tuxputenv("WSNADDR=//10.6.36.103:10115");
    	/* old format: lrt_tuxputenv("WSNADDR=0x000223600a062444");	 */
    	lr_think_time(5);
    	tpresult_int = lrt_tpinitialize(LRT_END_OF_PARMS);
    	if((sndBuf = (FBFR32*)lrt_tpalloc((char*)"FML32", NULL, len)) == NULL){
    		lr_output_message("TpQuery:tpalloc error");
    		return 0;
    	}
    
    	if((rcvBuf = (FBFR32*)lrt_tpalloc((char*)"FML32", NULL, len)) == NULL){
    		lr_output_message("TpQuery:tpalloc error");
    		return 0;
    	}
    	
    	return 0;
    }





VUser_end---------------------------------




    
    vuser_end()
    {
    	lrt_tpfree((char*)sndBuf);
    	lrt_tpfree((char*)rcvBuf);
    	lrt_tpterm();
    	return 0;
    }





**Over.!**
