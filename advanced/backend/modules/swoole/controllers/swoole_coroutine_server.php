<?php


$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function () {

    $server = new Swoole\Coroutine\Server("0.0.0.0", 6666);
    $server->handle(function ($conn) {
        while (true) {
            print_r($conn->recv());
            $conn->send("你好");
        }
    });
    $server->start();

});
$scheduler->start();


//协程服务端要在【协程内】使用
//print_r($conn);
//[fd] => 8
//[domain] => 2
//[type] => 1
//[protocol] => 0
//[errCode] => 0
//[errMsg] =>