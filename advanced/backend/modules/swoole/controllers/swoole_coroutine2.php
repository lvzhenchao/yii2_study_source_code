<?php

echo "start...".PHP_EOL;

$a = 123;

$scheduler = new Swoole\Coroutine\Scheduler;

//$scheduler->add(function ($c) {
//    Co::sleep(3);
//    echo "1111".PHP_EOL;
//}, $a);
//
//$scheduler->add(function ($c) {
//    echo "2222".PHP_EOL;
//}, $a);
//echo "end...".PHP_EOL;

$atomic = new Swoole\Atomic();

$scheduler->parallel(3,function () use ($atomic) {
    echo "aaaa". $atomic->get(). PHP_EOL;
    $atomic->add();
});

$scheduler->start();

//parallel()添加并行任务


