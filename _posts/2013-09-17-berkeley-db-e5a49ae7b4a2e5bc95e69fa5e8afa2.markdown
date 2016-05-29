---
author: me115wp
comments: true
date: 2013-09-17 04:45:10+00:00
layout: post
link: http://blog.me115.com/2013/09/326
slug: berkeley-db-%e5%a4%9a%e7%b4%a2%e5%bc%95%e6%9f%a5%e8%af%a2
title: Berkeley DB 多索引查询
wordpress_id: 326
categories:
- C++编程
- 程序员
tags:
- BerkeleyDB
---

由于性能原因，我们打算将关系型数据库转移到内存数据库中；在内存数据库产品的选型中，我们确定的候选对象有Redis和Berkeley DB；

 

Redis查询效率不错，并且支持丰富的数据存储结构，但不支持多索引，这样对于比较复杂的sql移植可能会造成数据膨胀；Berkeley DB只支持简单的Key/Value, 但支持多索引查询，对我们目前的应用来说，移植起来更有优势；

 

 

下面我们看看，如何为DB建立二级索引；

 

还是用例子来说明：

 

一张表中记录学生的信息；每个学生有个唯一的ID，这个id通常就是表的主键；

 

现在，我们希望通过学生的last_name来查询，这就需要建立二级索引；

 

注：用词约定：

 

* 本文提到的“数据库”是指Berkeley DB的database，相当于关系数据库的一个表。

 

作为SQL的常用表：

 
    
    <p>CREATE TABLE students(student_id CHAR(4) NOT NULL,lastname CHAR(15),</p><p> firstname CHAR(15), PRIMARY KEY(student_id));
    CREATE INDEX lname ON students(lastname);</p>





在Berkeley DB中，就是定义为如下结构：




    
    struct student_record {
        char student_id[4];
        char last_name[15];
        char first_name[15];
    };
    
    void second()
    {
        DB *dbp, *sdbp;
        int ret;
    
        /* 创建/打开第一个数据库*/
        if ((ret = db_create(&dbp, dbenv, 0)) != 0)
            handle_error(ret);
        if ((ret = dbp->open(dbp, NULL,
            "students.db", NULL, DB_BTREE, DB_CREATE, 0600)) != 0)
            handle_error(ret);
        /* 打开第二个数据库，注意，需要申明这个库支持重复记录，因为学生的last_name不是唯一的，是可能重复的*/  
        if ((ret = db_create(&sdbp, dbenv, 0)) != 0)
            handle_error(ret);
        if ((ret = sdbp->set_flags(sdbp, DB_DUP | DB_DUPSORT)) != 0)
            handle_error(ret);
        if ((ret = sdbp->open(sdbp, NULL,
            "lastname.db", NULL, DB_BTREE, DB_CREATE, 0600)) != 0)
            handle_error(ret);
    
        /* 将二级个库关联到第一个库上. 注：getname是提取key函数*/
        if ((ret = dbp->associate(dbp, NULL, sdbp, getname, 0)) != 0)
            handle_error(ret);
    }
    
    /*
    * getname -- 从第一个库的键值对中提取第二个库的key(即 last name)
    */
    int getname(DB *secondary, const DBT *pkey, const DBT *pdata, DBT *skey)
    {
        /*
         * 这里第二个key是数据的简单结构，所以并不需要做其它的工作，直接返回就完事。
         *  如果第二个key是需要从复杂记录中提取出来再组建，这个用户函数可能需要做分配空间和copy数据的工作；在这种情况下，对于第二个键的DBT结构需要设置 DB_DBT_APPMALLOC 标志位；*/
        memset(skey, 0, sizeof(DBT));
        skey->data = ((struct student_record *)pdata->data)->last_name;
        skey->size = sizeof(((struct student_record *)pdata->data)->last_name);
        return (0);
    }









**插入数据**





从开发者的角度来看，插入数据与第二个索引数据库无关，直接操作第一个数据库中即可：




    
    struct student_record s;
    DBT data, key;
    memset(&key, 0, sizeof(DBT));
    memset(&data, 0, sizeof(DBT));
    memset(&s, 0, sizeof(struct student_record));
    key.data = "WC42";
    key.size = 4;
    memcpy(&s.student_id, "WC42", sizeof(s.student_id));
    memcpy(&s.last_name, "Churchill      ", sizeof(s.last_name));
    memcpy(&s.first_name, "Winston        ", sizeof(s.first_name));
    data.data = &s;
    data.size = sizeof(s);
    if ((ret = dbp->put(dbp, txn, &key, &data, 0)) != 0)
        handle_error(ret);









**删除数据**





删除数据可以通过第一个索引（student_id）来删除，也可以通过第二个索引（last_name）来删除，无论使用哪个索引删除，被删除的都是第一个库中的真实数据；





eg： 使用第一个索引删除：




    
    BT key;
    memset(&key, 0, sizeof(DBT));
    key.data = "WC42";
    key.size = 4;
    if ((ret = dbp->del(dbp, txn, &key, 0)) != 0)
        handle_error(ret);









eg：使用二级个索引删除：








    
    DBT skey;
    memset(&skey, 0, sizeof(DBT));
    skey.data = "Churchill      ";
    skey.size = 15;
    if ((ret = sdbp->del(sdbp, txn, &skey, 0)) != 0)
        handle_error(ret);









这里需要注意的是，第二个索引并非唯一性索引，所以可能对应多条数据，执行删除操作，将删除所有对应的数据；









**查询数据**





使用第一个索引查询数据，使用DB->get()；





使用第二个索引查询数据，可使用DB->pget() 或者 DB->pget()





两者的区别就是，如果使用DB->pget() ，则会将查询到的数据对应的第一个索引key同时返回；（DBC->pget()也是这样）





这里给出两者的函数原型：




    
    #include <db_cxx.h>
    int Db::get(DbTxn *txnid, Dbt *key, Dbt *data, u_int32_t flags);
    int Db::pget(DbTxn *txnid, Dbt *key, Dbt *pkey, Dbt *data, u_int32_t flags); 
    pkey即第一索引的key；
    
    eg：
    DBT data, pkey, skey;
    memset(&skey, 0, sizeof(DBT));
    memset(&pkey, 0, sizeof(DBT));
    memset(&data, 0, sizeof(DBT));
    skey.data = "Churchill      ";
    skey.size = 15;
    if ((ret = sdbp->pget(sdbp, txn, &skey, &pkey, &data, 0)) != 0)
        handle_error(ret);









**错误处理**





在DS或CDS上更新二级索引时，可能会产生以下错误：





• 0





• DB_BUFFER_SMALL





• DB_NOTFOUND





• DB_KEYEMPTY





• DB_KEYEXIST





为了防止这些错误，在索引更新后，最好立刻删除这个二级索引，然后重建；





注意：DB_RUNRECOVERY 和 DB_PAGE_NOTFOUND属于严重级错误，一般不会发生；





如果Berkeley DB返回了这类错误，需要首先检查数据库的完整性（使用DB->verify())，确认没问题后再重建索引；









**总结**





一旦调用DB->associate() 将两个索引库关联起来，二级索引就成为第一数据库的另一个入口；





所有的更新操作都会影响与其关联的索引库；





在二级索引上，游标的操作函数都可正常使用；





需要指出的是，对于插入操作，BDB禁止通过二级索引来插入数据，因为那样的话，就没有方法为第一数据库指明主索引。应用程序，应该在第一个数据库上使用DB->put() or DBC->put()来插入数据；





可以对建立任意多个二级索引，BDB中对这方面没有限制；只要内存大小允许，以及文件描述符够用，理论上对于一个数据库可以建立任意多个二级索引；当然，索引不是越多越好，在数据更新时，索引的更新也是不小的代价；所以，设计阶段，对于索引的建立，需要精心的设计一二；





如果发现二级索引失效了，应该通过调用DB->remove()将其删除，同时，再调用一次DB->associate() 方法来生成新的索引；





如果二级索引库不再需要了，需要先关闭数据库句柄，DB->close()，再将其删除：DB->remove()；





关闭主索引库句柄时，会自动关闭所以与其关联的二级索引句柄；









**更多参考**





《Reference Guide for Berkeley DB》





[http://docs.oracle.com/cd/E17076_03/html/index.html](http://docs.oracle.com/cd/E17076_03/html/index.html)
