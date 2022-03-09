# PHP实现多进程
## pcntl_fork在当前进程当前位置产生分支（子进程）；fork是创建了一个子进程；
- 在父进程内，返回子进程号 大于0
- 在子进程内返回0
- 失败则返回-1

## 进程间的通信
- 管道通信
- 消息队列通信
- 进程信号通信
- 共享内存通信：是最快的IPC（进程间通信）方式
- 套接字通信
- 第三方通信：文件操作，mysql、redis等方式

## php默认并不支持多线程，要用多线程需要安装pthread扩展

## 线程安全：正确处理多个线程之间的【共享变量】，使程序功能正确完成
- php的TSRM机制对全局变量和静态变量进行了隔离，将全局变量和静态变量都复制了一份，各线程使用的都是主线程的一个备份，避免了变量冲突

# swoole的server参数详解 server(string $host, int $port = 0, int $mode = SWOOLE_PROCESS, int $sock_type = SWOOLE_SOCK_TCP)
- $host IPv4使用 127.0.0.1表示监听本机，0.0.0.0表示监听所有地址;IPv6使用::1表示监听本机，:: (相当于0:0:0:0:0:0:0:0) 表示监听所有地址
- $port 监听的端口；监听小于1024的端口需要root权限；端口被占用时server->start时会失败
- $mode 运行模式；SWOOLE_PROCESS多进程模式（默认）；SWOOLE_BASE基本模式
- $sock_type 指定socket类型；TCP(默认)、UDP、TCP6、UDP6、UnixSocket Stream/Dgram 

## $server->start();启动服务器；启动成功后会创建worker_num+2个进程
- Master 主进程：主进程内有多个Reactor线程，基于epoll/kqueue进行网络事件轮询
- Manager 进程：对所有Worker进程进行管理；
- Worker 进程：对收到的数据进行处理，包括协议解析和响应请求。未设置worker_num，底层会启动与CPU数量一致的Worker进程


