<?php


$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function () {

    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $val = $redis->set('user_name', "lzc");
    print_r($redis->get("user_name"));

});
$scheduler->start();


