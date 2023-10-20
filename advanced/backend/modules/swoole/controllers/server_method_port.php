<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$port =  $server->listen('0.0.0.0', 7777, SWOOLE_SOCK_TCP);

$port->on("receive", function ($serv, $fd, $reactor_id, $data){
    print_r($serv->getClientInfo($fd));
    echo '我是777端口'.$data.PHP_EOL;
});

$server->on("connect", function ($serv, $fd, $reactor_id) {
    print_r($serv->stats());
});

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的
    print_r($serv->getClientInfo($fd));
    echo '我是666端口'.$data.PHP_EOL;
});


$server->start();


//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

//stats()得到当前 Server 的活动 TCP 连接数，启动时间等信息

