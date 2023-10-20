<?php

//实例化server实例
//第一种
$server = new \Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);//SWOOLE_SOCK_TCP
//第二种：类名映射关系和上面的
//$server = new swoole_server('0.0.0.0', 8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//是个对象
//print_r($server);

$server->on("connect", function ($serv, $fd){//这个方法是TCP协议运行时必须有的
    echo "connected success...".$fd.PHP_EOL;
});

$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的
    echo "我是receive回调方法...data".$data.PHP_EOL;
});

//$server->on("packet", function (){//这个方法是UDP协议运行时必须有的
//    echo "我是packet回调方法";
//});

//swoole\Server::start(): require onReceive callback
//这个方法调用之前，需要一个onReceive的回调函数
$server->start();

//netstat -nltp 查看TCP端口号
//tcp        0      0 0.0.0.0:6666        0.0.0.0:*          LISTEN      19185/php

//netstat -nltup 查看UDP端口号
//udp        0      0 0.0.0.0:6666        0.0.0.0:*          19914/php


//start()启动后，会监听TCP/UDP端口
//启动成功后，会创建worker_num+2个进程。
//2个进程：Master进程+Manager进程
//Master主进程：内有多个Reactor线程[根据核数走的]，收到数据转发到worker进程去处理
//Manager进程：对所有worker进程进行管理，worker进程结束或者发生异常时自动回收，并创建新的Worker进程
//Worker进程：对收到的数据进行处理，包括协议解析和响应请求：【处理代码主要在这里】
//Task进程


//netstat -nltp 获取当前serve的运行端口：主进程的端口号
//pstree -p 22192 查看进程树
//php(22192)【master进程】─┬─php(22193)【manager进程】─┬─php(22196)【worker进程】
            //            │                           └─php(22197)【worker进程】
            //            ├─{php}(22194)【线程】
            //            └─{php}(22195)【线程】

// ps -ef | gerp php  这个主要看进程
//root     22192 21808  0 22:14 pts/0    00:00:00 php server.php
//root     22193 22192  0 22:14 pts/0    00:00:00 php server.php
//root     22196 22193  0 22:14 pts/0    00:00:00 php server.php
//root     22197 22193  0 22:14 pts/0    00:00:00 php server.php
























