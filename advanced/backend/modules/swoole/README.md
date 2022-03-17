#安装swoole
- 第一种：enable-swoole 编译PHP时直接编译进去
- 第二种：pecl install swoole
- 第三种：docker 安装
- 第四种：

`
    # Yaconf
    Yaconf是一个高性能的PHP配置容器， 它在PHP启动的时候把格式为INI的配置文件Parse后存储在PHP的常驻内存中
    php.ini增加一个默认文件指向：yaconf.directory = /home/mycode/php_video/ini
    ## 宝塔安装
    - 下载
    git clone https://github.com/laruence/yaconf
    - 进入源码目录
    cd yaconf
    - 生成配置
    /www/server/php/74/bin/phpize
    ./configure --with-php-config=/www/server/php/74/bin/php-config
    - 编译
    make && make install
    - 写配置文件
    echo "extension = yaconf.so" >> /www/server/php/74/etc/php.ini
    - 重启php
    - 检查
    php -m
`
# 进程：运行的程序；程序运行的载体；它的上一层是CPU
- ps -ef | grep php 查看PHP相关进程

`

    www        923  9328  0 22:18 ?        00:00:00 php-fpm: pool www
    root      2132  1487  0 22:26 pts/0    00:00:00 grep --color=auto php
    root      4458     1  0 06:13 ?        00:00:27 php-fpm: master process (/usr/local/php/etc/php-fpm.conf)
    vagrant   4462  4458  0 06:13 ?        00:00:00 php-fpm: pool www
    root      4499     1  0 06:13 ?        00:00:08 php-fpm: master process (/www/server/php/72/etc/php-fpm.conf)
    www       4501  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4502  4499  0 06:13 ?        00:00:04 php-fpm: pool www
    www       4503  4499  0 06:13 ?        00:00:04 php-fpm: pool www
    www       4504  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4505  4499  0 06:13 ?        00:00:04 php-fpm: pool www
    www       4506  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4507  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4508  4499  0 06:13 ?        00:00:04 php-fpm: pool www
    www       4509  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4510  4499  0 06:13 ?        00:00:03 php-fpm: pool www
    www       4578  4499  0 06:13 ?        00:00:04 php-fpm: pool www
    www       7420  4499  0 18:16 ?        00:00:00 php-fpm: pool www
    www       7422  4499  0 18:16 ?        00:00:00 php-fpm: pool www

`
# 线程：程序执行中的一个单一顺序控制流程，是程序执行流的最小单元，是处理器调度和分派的基本单位。一个进程可以有一个或多个线程


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
- Manager 进程：对所有Worker进程进行管理；Worker 进程生命周期结束或者发生异常时自动回收，并创建新的 Worker 进程
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
## 线程
- 可以同一时间同时执行多个线程，开辟多条线程开销很大，适合多任务同时处理，cpu密集型

## 协程
- 特性：极高的执行效率，因为不需要线程切换，而是有程序自身控制，没有现成切换的开销
- 适用场景：适合对【某任务】进行分时处理；IO密集型
- 协程在底层实现上是单线程的，因此同一时间只有一个协程在工作，协程的执行是串行的

`

    创建协程的方式一
    Co::create(function(){
        Co::sleep(2);
        echo "Done.\n";
    });
    
    创建协程的方式二 (将废弃)
    go(function () {
        Co::sleep(1);
        echo "Done.\n";
    });
    
    创建协程的方式三
    $scheduler = new Coroutine\Scheduler;
    $scheduler->add(function () {
        Co::sleep(1);
        echo "Done.\n";
    });
    $scheduler->start();
    
    创建协程的方式四
    Co\run(function () {
        Co::sleep(1);
        echo "Done.\n";
    });

    sleep() 可以看做是 CPU密集型任务, 不会引起协程的调度
    Co::sleep() 模拟的是 IO密集型任务, 会引发协程的调度
    
    适用场景
    
    协程非常适合编写
    
    1、高并发服务，如秒杀系统、高性能API接口、RPC服务器，使用协程模式，服务的容错率会大大增加，某些接口出现故障时，不会导致整个服务崩溃
    
    2、爬虫，可实现非常巨大的并发能力，即使是非常慢速的网络环境，也可以高效地利用带宽
    
    3、即时通信服务，如IM聊天、游戏服务器、物联网、消息服务器等等，可以确保消息通信完全无阻塞，每个消息包均可即时地被处理


`




































