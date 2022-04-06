<?php

$http = new Swoole\Http\Server('0.0.0.0', 6789);

$http->on('request', function ($request, $response) {
//    $response->end("hello end");//发送 Http 响应体，并结束请求处理
//    print_r($request);
//    print_r($request->get);

    //$response->header('content-type', 'image/jpeg', true);
//    $response->status('405', 'ss');
//    $response->cookie('lzc', 'hh');
    $response->write("hello write1");
    $response->write("hello write2");
});

$http->start();

//linux服务器内访问
//curl 127.0.0.1:6667

