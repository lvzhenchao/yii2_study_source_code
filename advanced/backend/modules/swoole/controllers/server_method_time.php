<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的
//    $serv->tick(200, function () {
//        echo "定时器：".PHP_EOL;
//    });
//    $serv->after(2000, function () {
//        echo "一次性定时器：".PHP_EOL;
//    });

    $timer_id = $serv->tick(200, function () {
        echo "定时器".PHP_EOL;
    });
    $serv->after(2000, function () use($serv, $timer_id) {
        $serv->clearTimer($timer_id);
        echo "清除定时器".PHP_EOL;
    });

});


$server->start();


//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

//tick(int $millisecond, mixed $callback)毫秒级别的定时器
//after(int $millisecond, mixed $callback)一次性定时器
//clearTimer($timer_id)清除定时器

