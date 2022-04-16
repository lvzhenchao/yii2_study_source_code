<?php

echo "start...".PHP_EOL;

$a = 123;

$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function ($c) {
    Co::sleep(3);
    echo "1111".PHP_EOL;
}, $a);

$scheduler->add(function ($c) {
    echo "2222".PHP_EOL;
}, $a);
echo "end...".PHP_EOL;
$scheduler->start();


//创建协程：
//1、Swoole\Coroutine::create
//2、Co::create
//3、new Coroutine\Scheduler [推荐]

//没有IO操作
//start...
//hello 协程
//end...

//有IO操作
//start...
//end...
//hello 协程

//sleep() 可以看做是CPU密集型任务，不会引起协程的调度
//CO::sleep() 模拟的是IO密集型任务，会引起协程的调度
