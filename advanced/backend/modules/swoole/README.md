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
- pstree  -p  进程号

## $server->set([]); 设置运行各项参数；服务启动后通过$serv->setting 来访问set()方法设置的参数数组
- max_conn最大连接，最大允许维持多少个tcp连接，超过此数量后，新连接会被拒绝
- max_request最大请求数
- reactor_num线程数，充分利用多核
- worker_num进程数，设置启动的进程数量
- task_worker_num配置task进程的数量；配置此参数将会启用task功能；并且需要注册onTask、onFinish2个事件回调函数
- log_file错误日志文件
- heartbeat_check_interval=>3 心跳检测机制，单位秒；swoole会轮询所有TCP连接，将超过心跳时间的连接关闭掉
- heartbeat_idle_time=>60 Tcp连接的最大闲置时间
- daemonize=>1 守护进程；执行php server.php 将转入后台作为守护进程运行
- enable_coroutine是否开启协程

## $server->on() 注册事件回调函数；
- 第一个参数：回调的名称，大小写不敏感
- 第二个参数：回调函数

## 回调函数4种写法
### 第一种 匿名函数
`

    $a = 'lampol';
    
    $server->on(‘start’,function($serv) use($a){  //use向匿名函数传递参数
            echo 'hello....'.$a;
    });

`
### 第二种 函数
`

    $server->on('start','test_start');
    
    function test_start($serv){
        echo 'master_pid......========'.$serv->master_pid;
    }

`
### 第三种 对象方法
`

    $test = new Test();
    $server->on('start',[$test,'test_start']);
    
    class Test{
    
        public  function test_start($serv){
            echo 'master_pid......========'.$serv->master_pid;
        }
    }

`
### 第三种 类静态方法

`

    $server->on('start','Test::test_start');
    
    class Test{
    
        public static function test_start($serv){
            echo 'master_pid......========'.$serv->master_pid;
        }
    }

`

