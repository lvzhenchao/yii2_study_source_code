<?php



Co::set([
    "max_coroutine" =>2,//同时的数量；如果并发的协程超过这个数量，会报错；如果下面的协程都带着Co::sleep()【阻塞着】,
    //相当于是并发的，就会报错
]);

//go(function () {
//    Co::sleep(1);
//    echo "123".PHP_EOL;
//});
//go(function () {
//    Co::sleep(1);
//    echo "456".PHP_EOL;
//});
//
//go(function () {
//    Co::sleep(1);
//    echo "789".PHP_EOL;
//});

$scheduler = new Swoole\Coroutine\Scheduler;

$scheduler->add(function ($c) {
//    Co::defer(function () {
//        echo "hello defer".PHP_EOL;
//    });

//    echo "hello co".PHP_EOL;
//    echo "cid：".Co::getCid().PHP_EOL;

    $cid = Co::getCid();
    echo "co 1 start\n";
    Co::yield();
    echo "co 1 end\n";
}, $a);

$scheduler->add(function () {
//    echo "hello co".PHP_EOL;
//    echo "cid：".Co::getCid().PHP_EOL;
    echo "co 2 start\n";

    foreach (Co::list() as $cid) {
        if ($cid == Co::getCid()) {
            continue;
        }
        Co::resume($cid);
    }
    echo "co 2 end\n";
}, $a);


$scheduler->start();

//defer();在协程函数执行完之前执行；类似与析构函数
//yield();手动让出执行权
//resume()恢复让出执行权的协程



















