<?php


echo "start...".PHP_EOL;

Co::create(function (){
    Co::sleep(3);//模拟IO操作
    echo "hello 协程1".PHP_EOL;
});

Co::create(function (){
    Co::sleep(3);//模拟IO操作
    echo "hello 协程2".PHP_EOL;
});

echo "end...".PHP_EOL;


//创建协程：
//1、Swoole\Coroutine::create
//2、Co::create

//没有IO操作
//start...
//hello 协程
//end...

//有IO操作
//start...
//end...
//hello 协程
