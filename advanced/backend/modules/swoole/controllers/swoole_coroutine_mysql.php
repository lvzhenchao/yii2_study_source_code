<?php


$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function () {

    $swoole_mysql = new Swoole\Coroutine\MySQL();
    $swoole_mysql->connect([
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'user'     => 'root',
        'password' => 'BspKCZLRZWeHeaTR',
        'database' => 'yii',
    ]);
    $res = $swoole_mysql->query('select * from address');
    print_r($res);

});
$scheduler->start();


