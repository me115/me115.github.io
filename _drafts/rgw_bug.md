# 排查 ceph object gateway 早期版本 bug

docker registry 版本为2.5.0，ceph的版本为9.0.3，最开始我使用的ceph oject gateway的为最新版10.2.2（因为ceph集群是很早就搭建了，并在线上稳定运行）；
最新版本的ceph oject gateway 和 ceph 交互有问题， gw的新 put 操作 ceph 会报错不支持；
然后我将ceph gateway的版本更改为 和 ceph 一样的版本9.0.3；结果发现rgw的9.0.3的 Head请求有bug； 
docker registry PUT操作存放数据之后会跟一个HEAD请求验证是否存入；这个head请求返回没有http status；
跟踪代码后确定是civetweb 的问题；
因为civetweb和rgw的编译是独立的；
于是直接将civetweb升级到最新版；
同时考虑到9.0.*是ceph的开发版本，并不保证充分的测试，将其升级到离ceph9.0.3最近的一个稳定版9.1.0;
这样，我的rgw组合就是rgw9.1.0+civetweb 1.8.0；问题解决；


## 问题现象
使用9.0.3版的ceph rgw，安装完成后使用swift client测试没有问题，使用脚本测试swift 接口，可以正常put/get object、create/list continer;

对接docker registry之后，docker push的时候总是重试，无法推送上去；
```shell
root@n6-026-137:registry# docker push n6-026-137.byted.org:443/busybox:v1.0
The push refers to a repository [n6-026-137.byted.org:443/busybox]

5f70bf18a086: Retrying in 2 seconds 
1834950e52ce: Retrying in 2 seconds 
```

查看 docker registry的日志：
registry logs 显示 "malformed HTTP status code \"0\"""
```shell
^Croot@n6-026-137:registry# docker logs 00f46850b795 | grep 2a1874b5-c7c5-4cb8-ad57-a7cb9411
time="2016-08-02T04:01:06Z" level=error msg="error resolving upload: swift: Head http://10.6.26.136:9980/swift/v1/registry136/files/docker/registry/v2/repositories/busybox/_uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892/data: malformed HTTP status code \"0\"" go.version=go1.6.3 http.request.host="n6-026-137.byted.org:443" http.request.id=0a207311-5329-4335-b364-9ed4286e38f3 http.request.method=PATCH http.request.remoteaddr=10.4.16.31 http.request.uri="/v2/busybox/blobs/uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892?_state=EWusoqRBXI0Ytu-3mW1nHWWQzA-iRVfbNCg_RUZpqZB7Ik5hbWUiOiJidXN5Ym94IiwiVVVJRCI6IjJhMTg3NGI1LWM3YzUtNGNiOC1hZDU3LWE3Y2I5NDExMDg5MiIsIk9mZnNldCI6MCwiU3RhcnRlZEF0IjoiMjAxNi0wOC0wMlQwNDowMDo1Mi43NjQwMzA5MDJaIn0%3D" http.request.useragent="docker/1.12.0 go/go1.6.3 git-commit/8eab29e kernel/3.16.0-4-amd64 os/linux arch/amd64 UpstreamClient(Docker-Client/1.12.0 \\(linux\\))" instance.id=4ec8a7f3-459c-40f0-a534-f4a4eff856fa vars.name=busybox vars.uuid=2a1874b5-c7c5-4cb8-ad57-a7cb94110892 version=v2.5.0 
time="2016-08-02T04:01:06Z" level=error msg="response completed with error" err.code=unknown err.detail="swift: Head http://10.6.26.136:9980/swift/v1/registry136/files/docker/registry/v2/repositories/busybox/_uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892/data: malformed HTTP status code \"0\"" err.message="unknown error" go.version=go1.6.3 http.request.host="n6-026-137.byted.org:443" http.request.id=0a207311-5329-4335-b364-9ed4286e38f3 http.request.method=PATCH http.request.remoteaddr=10.4.16.31 http.request.uri="/v2/busybox/blobs/uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892?_state=EWusoqRBXI0Ytu-3mW1nHWWQzA-iRVfbNCg_RUZpqZB7Ik5hbWUiOiJidXN5Ym94IiwiVVVJRCI6IjJhMTg3NGI1LWM3YzUtNGNiOC1hZDU3LWE3Y2I5NDExMDg5MiIsIk9mZnNldCI6MCwiU3RhcnRlZEF0IjoiMjAxNi0wOC0wMlQwNDowMDo1Mi43NjQwMzA5MDJaIn0%3D" http.request.useragent="docker/1.12.0 go/go1.6.3 git-commit/8eab29e kernel/3.16.0-4-amd64 os/linux arch/amd64 UpstreamClient(Docker-Client/1.12.0 \\(linux\\))" http.response.contenttype="application/json; charset=utf-8" http.response.duration=71.35822ms http.response.status=500 http.response.written=274 instance.id=4ec8a7f3-459c-40f0-a534-f4a4eff856fa vars.name=busybox vars.uuid=2a1874b5-c7c5-4cb8-ad57-a7cb94110892 version=v2.5.0 
10.6.26.137 - - [02/Aug/2016:04:01:06 +0000] "PATCH /v2/busybox/blobs/uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892?_state=EWusoqRBXI0Ytu-3mW1nHWWQzA-iRVfbNCg_RUZpqZB7Ik5hbWUiOiJidXN5Ym94IiwiVVVJRCI6IjJhMTg3NGI1LWM3YzUtNGNiOC1hZDU3LWE3Y2I5NDExMDg5MiIsIk9mZnNldCI6MCwiU3RhcnRlZEF0IjoiMjAxNi0wOC0wMlQwNDowMDo1Mi43NjQwMzA5MDJaIn0%3D HTTP/1.1" 500 274 "" "docker/1.12.0 go/go1.6.3 git-commit/8eab29e kernel/3.16.0-4-amd64 os/linux arch/amd64 UpstreamClient(Docker-Client/1.12.0 \\(linux\\))"
```

检查rgw日志，发现swift 接口在处理http HEAD的时候出了问题：
```shell
2016-08-02 12:00:53.287357 7f9c897fa700  2 req 18135:0.221205:swift:GET /swift/v1/registry136:list_bucket:http status=200
2016-08-02 12:00:53.287415 7f9c897fa700  1 ====== req done req=0x7f9c6c004e00 http_status=200 ======
2016-08-02 12:00:53.287452 7f9c897fa700  1 civetweb: 0x7f9c6c0008c0: 10.6.26.137 - - [02/Aug/2016:12:00:5
3 +0800] "GET /swift/v1/registry136 HTTP/1.1" 200 0 - distribution/v2.5.0

2016-08-02 12:00:53.342098 7f9c88ff9700  2 req 18136:0.265363:swift:HEAD /swift/v1/registry136/files/dock
er/registry/v2/repositories/busybox/_uploads/2a1874b5-c7c5-4cb8-ad57-a7cb94110892/data:get_obj:http status=200
2016-08-02 12:00:53.342112 7f9c88ff9700  1 ====== req done req=0x7f9c70017db0 http_status=200 ======
2016-08-02 12:00:53.342153 7f9c88ff9700  1 civetweb: 0x7f9c700008c0: 10.6.26.137 - - [02/Aug/2016:12:00:5
3 +0800] "HEAD /swift/v1/registry136/files/docker/registry/v2/repositories/busybox/_uploads/2a1874b5-c7c5
-4cb8-ad57-a7cb94110892/data HTTP/1.1" -1 0 - distribution/v2.5.0
```
最后一句有bug：
```shell
"HEAD /swift/v1/registry136/files/docker/registry/v2/repositories/busybox/_uploads/2a1874b5-c7c5
-4cb8-ad57-a7cb94110892/data HTTP/1.1" -1 0 - distribution/v2.5.0
```

使用tcpdump抓包后，清晰的显示整个流程中 HEAD返回的请求有问题（无http status code）：
![]()


把rgw 9.0.3的代码拉下来跟了下：
主流程清晰简单，跟着日志一步步，主流程的最后一个日志是：
```shell
  dout(1) << "====== req done req=" << hex << req << dec << " http_status=" << http_ret << " ======" << dendl;
```
日志为：
```shell
2016-08-02 12:00:53.342112 7f9c88ff9700  1 ====== req done req=0x7f9c70017db0 http_status=200 ======
```
状态码没有问题；

而最后一句的错误日志是通过 rgw_civetweb_log_access_callback 打印的：
```shell
int rgw_civetweb_log_access_callback(const struct mg_connection *conn, const char *buf) {
  dout(1) << "civetweb: " << (void *)conn << ": " << buf << dendl;
  return 0;
}
```

最后一句错误日志是mongoose的回调打印出来的：
```shell
    struct mg_callbacks cb;
    memset((void *)&cb, 0, sizeof(cb));
    cb.begin_request = civetweb_callback;
    cb.log_message = rgw_civetweb_log_callback;
    cb.log_access = rgw_civetweb_log_access_callback;
    ctx = mg_start(&cb, &env, (const char **)&options);
```

接下来就只能跑到mongoose中继续排查：从0.80版本之后，gw基于civetweb来部署，不再依赖apache，但ceph/civetweb的版本不清晰。早期的9.0.3对应的mongoose是哪个无法对应上；（ps：civetweb based on the Mongoose web server）
civetweb和rgw的编译是独立的；
直接将civetweb升级到最新版；
而考虑是9.0.*是ceph的开发版本，并不保证充分的测试，将其升级到离ceph9.0.3最近的一个稳定版9.1.0;
这样，我的rgw就是9.1.0+civetweb 1.8.0；
源码编译，测试，通过；

（ceph编译过程有些曲折，参见：）