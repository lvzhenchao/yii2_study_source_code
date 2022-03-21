<?php

//实例化server实例
//第一种
$server = new \Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);//SWOOLE_SOCK_TCP
//第二种：类名映射关系和上面的
//$server = new swoole_server('0.0.0.0', 8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//是个对象
//print_r($server);

//$server->on("receive", function (){//这个方法是TCP协议运行时必须有的
//    echo "我是receive回调方法";
//});

$server->on("packet", function (){//这个方法是UDP协议运行时必须有的
    echo "我是packet回调方法";
});

//swoole\Server::start(): require onReceive callback
//这个方法调用之前，需要一个onReceive的回调函数
$server->start();

//netstat -nltp 查看TCP端口号
//tcp        0      0 0.0.0.0:6666        0.0.0.0:*          LISTEN      19185/php

//netstat -nltup 查看UDP端口号
//udp        0      0 0.0.0.0:6666        0.0.0.0:*          19914/php