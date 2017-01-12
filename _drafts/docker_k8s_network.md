# docker和kubernetes网络方案

## docker的网络实现方案（bridge模式）
docker常用的网络模式有host模式和bridge模式，host模式中所有容器和宿主机共享一个网络命名空间，没有网络隔离，无法由应用自由选择端口，在集群中没有应用；
kubernetes中使用的了docker的bridge模式，下面介绍bridge模式：

bridge模式由以下几个部分实现：
1. docker0虚拟网桥
   Docker daemon启动的时候会创建一个虚拟网桥，即docker0；
2. 独立网络的命名空间
   使用bridge模式，每个容器启动的时候会新建一个独立的网络命名空间，有了隔离的网络空间，在容器内运行的服务就可以使用任意端口而不用担心和宿主机上其他服务冲突；
3. veth设备对
   使用bridge模式启动容器时，同时创建一个veth设备对，连接容器和docker0虚拟网桥；这样就实现了容器和虚拟网桥的通信；宿主机上的所有容器都和docker0虚拟网桥连接在一块，他们就可以通过虚拟网桥相互通信；不同主机上的容器无法相互通信（这里体现了docker的最开始的设计思想，只考虑单机的场景，最小化设计，保持简单，至于集群，让编排软件来考虑）
   其中，veth设备对在新建的容器一侧的虚拟网卡接口被命名为eth0；

### 容器间如何相互访问？
比如上图中，ip1为物理节点网卡ip：10.6.26.137，
ip2 为 docker0 的ip：172.17.42.1
ip3 为 容器 eth0的 ip：172.17.0.19
docker 保证新建的容器分配的 ip地址和 docker0的ip是同一个网段；

container1和container2 通过 docker0 网桥能相互访问；
而该主机外如果希望访问到 container1 的ip，则需要在物理节点的路由规则表中增加路由规则，指明所有到 container1的ip的请求都转发到 docker0网关；这样外面就可访问到容器内部的ip；

## kubernetes

### kubernetes的网络模型

#### ip-per-pod模型
kubernetes 在容器和docker0之间加了一层抽象-- pod； docker的原生网络模型是 ip-per-container（一个容器一个ip），而 kubernetes 的网络模型是 ip-per-pod（一个pod一个ip），一个pod内可有多个容器，pod内部的容器共享同一网络栈；
这样，同一个pod内的容器可以通过localhost来访问到其他容器内的服务；

#### 容器间如何访问
如上图所示，container1 和container2 同属于一个pod中，共享同一个网络命名空间，共用同一个ip（ip3）；
所以 container1 中用到的端口，container2就不能使用；container1 和container2 通过localhost能访问彼此容器中的应用；
container1 和 container3 之间跨 pod了，它们属于同一节点上的不同pod，由于通过同一个 docker0 网桥相连，所以 它们能通过 ip 相互通信；
而节点间的pod 通信，就需要使用额外的网络方案来保证；

#### pod设计的优点
- 解决紧密耦合的容器之间的直接通信
  关系紧密的两个容器之间就可直接通信，不用使用服务发现来增加系统的复杂度；
  eg：比如python 服务的 nginx+uwsgi 部署方案，nginx和 uwsgi分别部署在两个容器， 对外暴露的pod 提供的是一个服务；

- 服务以pod为粒度进行封装
  pod之间没有直接关系；对于业务上的关联关系，通过服务发现来寻找到依赖服务，
  pod之间的通信通过k8s的网络方案来保证； 

### 对网络的需求
要保证所有的容器/POD 可以相互访问；
pod 的ip要在集群中能够唯一达到，那么 pod的ip 生成策略必须是基于整个集群来考虑，而不能是单个节点；
pod的ip和pod所在物理节点的ip能够关联起来，这样，就能通过pod的ip找到其所在节点的ip，从而将数据包路由到目标节点，进而传递到pod中；

也就是说，满足 k8s 的网络方案需要实现：
1. 整个集群中对pod的ip分配进行规划，不能冲突
2. 能将pod的ip和pod所在node的ip关联起来，能让各个pod相互访问；

### k8s的网络解决方案
要实现k8s的网络方案，最简单的是使用直接路由配置，由于设计到人工配置，适合小规模及开发测试时使用；
flannel 是overlay的方案，TCE目前使用的网络方案是这个；
calico 是完全的三层网络方案，没有封包解包，是目前比较理想的方案；后续TCE考虑使用这个；
下面看看各个方案如何满足k8s对网络的要求；

#### 直接路由
- 人工规划和配置每个宿主机上的docker0的ip，保证集群中docker0 的网关ip不冲突，由于网关ip不一样，从而保证了网关上建立的容器的ip在集群中的唯一；
- 通过动态路由软件（quagga）将本地路由表扩散到整个集群，让集群间的容器可相互访问；

#### flannel
- flannel 可以给每个docker容器分配互相不冲突的ip地址
- 能在这个ip地址之间建立一个叠加网络（overlay network），通过这个网络，使得每个容器间能相互通信；

#### calico
- 每个节点上运行Calico Agent（Felix），用来配置本节点的路由，整个集群的ip信息在etcd中保存，通过中心化的接口保证生成的ip的唯一性；
- BGP Client（BIRD）, 负责把Felix写入Kernel的路由信息分发到Calico网络，让集群间相互通信；

calico的优点：
- 简单可控，没有封包解包，节约CPU计算资源的同时，提高了整个网络的性能。
- 完全的三层网络方案，相比二层网络，调试方便；

看上去calico和直接路由方案比较相似，对比下它们的异同点：

与直接路由的相似点：都是完全的三层网络方案，通过路由交互，没有封包解包的过程；
与直接路由的区别： 

1）节点上的ip不用人工配置；
- calico方案中容器可选的ip地址范围是全局唯一的；直接路由配置的是docker0网关地址唯一，从容器的网段上保证了集群的唯一性，而calico 直接给容器分配全局唯一的ip地址（具体的操作：由felix 与etcd通信，从全局ip可选的范围中分配一个唯一的ip）

2）路由经过的路线不同
- 直接路由报文的经过的路线： 从容器内ip veth到 docker0网桥 路由到 宿主机 路由到其他宿主机 路由到 docker0网桥 veth对到 目的容器
- calico报文经过的路线：从容器内ip veth到 宿主机 路由到其他宿主机 veth对到 目的容器；
  calico通过容器直接路由到宿主机，没有经过docker0网桥；

3） 适合的规模不同
- 直接路由宿主机之间没有中转路由，适合小规模场景；
- calico 对于集群规模大的场景，引入一个新的组件BGP Route Reflector（BIRD），通过一个或者多个BGP Route Reflector来完成集中式的路由分发；


calico的核心是每个物理节点上的agent（felix），通过这个agent给每个容器分配一个唯一的ip，并配置容器内到宿主机ip的路由规则，从而保证到达host的报文正确转发：
![](http://docs.projectcalico.org/en/1.3.0/_images/calico-connectivity.png)

ref：
http://docs.projectcalico.org/en/1.3.0/addressing.html

http://dockone.io/article/1489








