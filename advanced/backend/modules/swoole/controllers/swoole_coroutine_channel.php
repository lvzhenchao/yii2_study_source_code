<?php


$scheduler = new Swoole\Coroutine\Scheduler;

$chan = new Swoole\Coroutine\Channel();

$scheduler->add(function ($chan) {
    $chan->push(['user_name'=>"lzc"]);
    $chan->push(['user_name1'=>"zhh"]);
    print_r($chan->length());
}, $chan);

$scheduler->add(function ($chan)  {


    print_r($chan->pop());
    print_r($chan->pop());
}, $chan);

//$scheduler->add(function ($chan)  {
//    print_r($chan->pop());
//}, $chan);

$scheduler->start();