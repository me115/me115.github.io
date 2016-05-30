---
author: me115wp
comments: true
date: 2015-12-30 07:00:21+00:00
layout: post
link: http://blog.me115.com/2015/12/907
slug: reactor%e4%ba%8b%e4%bb%b6%e9%a9%b1%e5%8a%a8%e7%9a%84%e4%b8%a4%e7%a7%8d%e5%ae%9e%e7%8e%b0%ef%bc%9a%e9%9d%a2%e5%90%91%e5%af%b9%e8%b1%a1-vs-%e5%87%bd%e6%95%b0%e5%bc%8f%e7%bc%96%e7%a8%8b
title: Reactor事件驱动的两种实现：面向对象 VS 函数式编程
wordpress_id: 907
categories:
- 网络编程
tags:
- muduo
- Reactor
- 网络编程
---

# Reactor事件驱动的两种实现：面向对象 VS 函数式编程





这里的函数式编程的设计以muduo为例进行对比说明；





## Reactor实现架构对比





面向对象的设计类图如下：





[![oo_class](http://blog.me115.com/wp-content/uploads/2015/12/oo_class_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/oo_class1.jpg)       






函数式编程以muduo为例，设计类图如下：





[![muduo](http://blog.me115.com/wp-content/uploads/2015/12/muduo_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/muduo1.jpg)   






## 面向对象的Reactor方案设计





我们先看看面向对象的设计方案，想想为什么这么做；     
拿出Reactor事件驱动的模式设计图，对比来看，清晰明了；





![reactor_model](http://blog.me115.com/wp-content/uploads/2015/12/reactor_model.jpg)   






从左边开始，事件驱动，需要一个事件循环和IO分发器，EventLoop和Poller很好理解；为了让事件驱动支持多平台，Poller上加一个继承结构，实现select、epoller等IO分发器选用；





Channel是要监听的事件封装类，核心成员：fd文件句柄；     
成员方法围绕着fd展开展开，如关注fd的读写事件、取消关注fd的读写事件；      
核心方法：      
enableReading/Writing;      
disableReading/Writing;      
以及事件到来后的处理方法：      
handleEvent；      
在OO设计这里，handleEvent设计成一个虚函数，回调上层实际的数据处理；





AcceptChannel和ConnetionChannel派生自Channel，负责实际的网络数据处理；根据职责的不同而区分，AcceptChannel用于监听套接字，接收新连接请求；有新的请求到来时，生成新的socket并加入到事件循环，关注读事件；     
ConnetionChannel用于真实的用户数据处理，处理用户的读写请求；涉及到具体的数据处理，当然，在这里会需要用到应用层的缓存区；





比较困难的是用户逻辑层的设计；放在哪里合适？     
先看看需求，用户逻辑层需要知道的事件点（在这之后可能会有应用层的逻辑）：      
连接建立、消息到来、消息发送完毕、连接关闭；      
这四个事件的源头是Channel的handleEvent()，直接调用者应该Channel的派生类（AcceptChannel和ConnetionChannel），貌似可以将用户逻辑层的指针放到Channel里；      
且不说架构上是否合理，单是实现上右边Channel这一块（含AcceptChannel和ConnetionChannel）对用户是透明的，用户只需要关注以上四个事件点，底层的细节用户层并不关心（比如是否该在事件循环中关注某个事件，取消关注某个事件，对用户都是透明的），所以外部用户无法直接将用户逻辑层的指针给Channel；





想想用户与网络库的接口在哪里？     
IO分发器对用户也是透明的，用户可见就是EventLoop，在main方法中：




    
    <code>EventLoop loop; 
    loop.loop();</code>





用户逻辑层也就只有通过EventLoop与Channel的派生类关联上；
    
这样，就形成的最终的设计类图，在main方法中：




    
    <code>UserLogicCallBack callback;
    EventLoop loop(&callback); //在定义 EventLoop时，将callback的指针传入，供后续使用；
    loop.loop();</code>





而网络层调用业务层代码时，则通过eventloop_的过渡调用到业务逻辑的函数；
    
比如ConnetionChannel中数据到达的处理：




    
    <code>eventloop_->getCallBack()->onMessage(this);</code>





## 函数式编程的Reactor设计





函数式编程中，类之间的关系主要通过组合来实现，而不是通过派生实现；
    
整个类图中仅有Poller处使用了继承关系；其它的都没有使用；

    
这也是函数式编程的一个设计理念，更多的使用组合而不是继承来实现类之间的关系，而支撑其能够这样设计的根源在于function()+bind()带来的函数自由传递，实现回调非常简单；

    
而OO设计中，只能使用基于虚函数/多态来实现回调，不可避免的使用继承结构;





下面再看看各个类的实现；
    
事件循环EventLoop和IO分发器没有区别；

    
Channel的职责也和上面类似，封装事件，所不同的是，Channel不再是继承结构中的基类，而是作为一个实体；

    
这样，handleEvent方法就不再是一个纯虚函数，而是包含具体的逻辑处理，当然，只有最基本的事件判断，然后调用上层的读写回调：




    
    <code>void Channel::handleEvent()
    {
      if (revents_ & (POLLIN | POLLPRI | POLLRDHUP))
      {
        if (readCallback_) readCallback_();
      }
      if (revents_ & POLLOUT)
      {
        if (writeCallback_) writeCallback_();
      }
    }</code>





这样的关键是设置一堆回调函数，通过boost::function()+boost::bind()可以轻松的做到；





### Acceptor 和TcpConnection





Acceptor类，这个对应到上面的AcceptChannel，但实现不是通过继承，而是通过组合实现；
    
Acceptor用于监听，关注连接，建立连接后，由TCPConnection来接管处理；

    
这个类没有业务处理，用来处理监听和连接请求到来后的逻辑；

    
所有与事件循环相关的都是channel，Acceptor不直接和EventLoop打交道，所以在这个类中需要有一个channel的成员，并包含将channel挂到事件循环中的逻辑（listen()）；

    
TcpConnection,处理连接建立后的收发数据；业务处理回调完成；





### TCPServer





TCPServer就是胶水，作用有二：






  
  1. 作为最终用户的接口方，和外部打交道通过TCPServer交互，而业务逻辑处理将回调函数传入到底层，这种传递函数的方式犹如数据的传递一样自然和方便； 


  
  2. 作用Acceptor和TcpConnection的粘合剂，调用Acceptor开始监听连接并设置回调，连接请求到来后，在回调中新建TcpConnection连接，设置TcpConnection的回调（将用户的业务处理回调函数传入，包括：连接建立后，读请求处理、写完后的处理，连接关闭后的处理），从这里可以看到，业务逻辑的传递就跟数据传递一样，多么漂亮； 





## 示例对比





通过一个示例来体会这两种实现中回调实现的差别；
    
示例：分析读事件到来时，底层如何将消息传递给用户逻辑层函数来处理的？





### OO实现





channel作为事件的监听接口，加入到事件循环中，当读事件到来时，需要调用
    
ConnetionChannel上的handleEvent();而异步数据的读请求最终需要业务逻辑层来判断是否读到相应的数据，这就需要从ConnetionChannel中调用用户逻辑层上的OnMessage();

    
看看这段逻辑的OO实现序列图：





[![oo_seq_msg](http://blog.me115.com/wp-content/uploads/2015/12/oo_seq_msg_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/oo_seq_msg1.jpg)   






代码层面的实现：
    
定义用户逻辑处理类UserLogicCallBack，接收消息的处理函数为onMessage()；

    
我们关注最终底层是如何调用到业务逻辑层的onMessage()的；




    
    <code>int main()
    {
        UserLogicCallBack urlLogic;
        EventLoop loop(urlLogic);//将用户逻辑对象与事件循环对象关联起来
        loop.loop();
    }</code>





callback_用户逻辑层的对象在EventLoop初始化时传入：




    
    <code>class EventLoop{
        EventLoop(CallBack & callback):
            callback_(callback)
        {
        }
        CallBack* getCallBack()
        {
            return &callback_;
        }
        CallBack& callback_; //回调方法基类
    }</code>





当读事件到来，在ConnectionChannel中通过eventloop对象作为桥梁，回调消息业务处理onMesssage();




    
    <code>void ConnectionChannel::handleRead(){
          int savedErrno = 0;
        //返回缓存区可读的位置，返回所有读到的字节,具体到是否收全，
        //是否达到业务需要的数据字节数，由业务层来判断处理
        ssize_t n = inputBuffer_.readFd(fd_, &savedErrno);
        if (n > 0)
        {    
                    //通过eventloop作为中介，调用业务层的回调逻辑
            loop_->getCallBack()->onMesssage(this,&inputBuffer_);
        }
        else if (n == 0)
        {
            handleClose();
        }
        else
        {
            errno = savedErrno;
            handleError();
        }
    }</code>





### 函数式编程实现





而muduo的回调，使用boost::function()+boost::bind()实现，通过这两个神器，将使用者和实现者解耦；
    
通过TcpServer，将用户逻辑层的函数传递到底层；读事件到来，回调用户逻辑；





以下是时序





[![fun_seq_msg](http://blog.me115.com/wp-content/uploads/2015/12/fun_seq_msg_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/fun_seq_msg1.jpg)   






代码层面，我们看看用户逻辑层的代码是如何传入的：
    
UserLogicCallBack中包含TcpServer的对象；




    
    <code>TcpServer server_;</code>





在构造函数中，将onMessage传递给TcpServer，这是第一次传递： 




    
    <code>UserLogicCallBack::UserLogicCallBack(muduo::net::EventLoop* loop,
                           const muduo::net::InetAddress& listenAddr)
      : server_(loop, listenAddr, "UserLogicCallBack")
    {
      server_.setConnectionCallback(
          boost::bind(&UserLogicCallBack::onConnection, this, _1));
      //这里将onMessage传递给TcpServer
      server_.setMessageCallback(
          boost::bind(&UserLogicCallBack::onMessage, this, _1, _2, _3));
    }</code>





TcpServer中的相关细节：




    
    <code>class TcpServer{
        void setMessageCallback(const MessageCallback& cb)
        { messageCallback_ = cb; }
    
        typedef boost::function<void (const TcpConnectionPtr&,
                                      Buffer*,
                                      Timestamp)> MessageCallback;
        MessageCallback messageCallback_;
    };</code>





TcpServer新建连接时，将用户层的回调函数继续往底层传递，这是第二次传递：




    
    <code>void TcpServer::newConnection(int sockfd, const InetAddress& peerAddr)
    {
      TcpConnectionPtr conn(new TcpConnection(ioLoop,
                                              connName,
                                              sockfd,
                                              localAddr,
                                              peerAddr));
      conn->setConnectionCallback(connectionCallback_);
      // 这里将onMessage()传递给TcpConnection
      conn->setMessageCallback(messageCallback_); 
      conn->setWriteCompleteCallback(writeCompleteCallback_);
      conn->setCloseCallback(boost::bind(&TcpServer::removeConnection, this, _1)); 
      ioLoop->runInLoop(boost::bind(&TcpConnection::connectEstablished, conn));
    }</code>





通过这两次传递，messageCallback_作为成员变量保存在TcpConnection中；
    
当读事件到来时，TcpConnection中就可以直接调用业务层的回调逻辑：




    
    <code>void TcpConnection::handleRead(Timestamp receiveTime)
    {
      //返回缓存区可读的位置，返回所有读到的字节,具体到是否收全，
      //是否达到业务需要的数据字节数，由业务层来判断处理
      ssize_t n = inputBuffer_.readFd(channel_->fd(), &savedErrno);
      if (n > 0)
      {
        //回调业务层的逻辑
        messageCallback_(shared_from_this(), &inputBuffer_, receiveTime);
      }
      else if (n == 0)
      {
        handleClose();
      }
      else
      {
        errno = savedErrno;
        handleError();
      }
    }</code>





完整时序详见最后一节；源代码来自muduo库；





## 两者的时序图对比





Reactor的面向对象编程时序：





[![oo_sequence](http://blog.me115.com/wp-content/uploads/2015/12/oo_sequence_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/oo_sequence1.jpg)









Reacotr的函数式编程时序：





[![EchoServer_sequence](http://blog.me115.com/wp-content/uploads/2015/12/EchoServer_sequence_thumb.jpg)](http://blog.me115.com/wp-content/uploads/2015/12/EchoServer_sequence1.jpg)   






## 结论





在面向对象的设计中，事件底层回调上层逻辑，本来和loop这个发送机没有任何关系的一件事，却需要使用它来作为中转；EventLoop作为回调的中间桥梁，实在是迫不得已的实现；
    
而muduo的设计中加入了TcpServer这一胶水层，整个架构就清晰多了；

    
boost::function()+boost::bind()让我们在回调的实现上有了更大的自由度，不用再依赖于基于虚函数的多态继承结构；但更大的自由度，也更容易带来糟糕的设计，使用boost::function()+boost::bind()基于对象的设计，还需要多多体会，熟练应用；





Posted by: 大CC | 30DEC,2015
    
博客：[blog.me115.com](http://blog.me115.com) [[订阅](http://blog.me115.com/feed)]

    
Github：[大CC](https://github.com/me115)



