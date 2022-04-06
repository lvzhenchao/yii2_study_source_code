<?php

$http = new Swoole\Http\Server('0.0.0.0', 6789);

$http->on('request', function ($request, $response) {
    $response->end("hello http");
});

$http->start();

//linux服务器内访问
//curl 127.0.0.1:6667

