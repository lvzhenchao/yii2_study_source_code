<?php


//echo "start...".PHP_EOL;

//模拟多线程的运行
/*Co::create(function (){
    Co::sleep(3);//模拟IO操作
    echo "hello 协程1".PHP_EOL;
});

Co::create(function (){
    sleep(3);
    Co::sleep(1);//模拟IO操作
    echo "hello 协程2".PHP_EOL;
});*/

//echo "end...".PHP_EOL;

echo "start...".PHP_EOL;
//有问题：多个协程才会没问题
//sleep(3);
//Co::sleep(1)

$scheduler = new Swoole\Coroutine\Scheduler;
$scheduler->add(function () {
    Co::sleep(3);
    echo "hello 协程容器".PHP_EOL;
});
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
