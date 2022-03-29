<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的

//    echo "hello world";
    $serv->send($fd, "hello ".$data);//向客户端发送数据
});

$server->start();

//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

