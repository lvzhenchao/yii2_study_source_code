<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->set([
    'heartbeat_idle_time'      => 15, // 表示一个连接如果600秒内未向服务器发送任何数据，此连接将被强制关闭
    'heartbeat_check_interval' => 5,  // 表示每60秒遍历一次
]);

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的


});


$server->start();


//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

//heartbeat()

