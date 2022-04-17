<?php


$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function () {

    $server = new Swoole\Coroutine\Http\Server("0.0.0.0", 6789);
    $server->handle("/", function ($request, $response) {
//        print_r($request);
        $response->end("hello coroutine http");
    });
    $server->start();

});
$scheduler->start();


