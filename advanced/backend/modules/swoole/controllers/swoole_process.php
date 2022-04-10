<?php

$process = new Swoole\Process(function ($pro){//子进程创建成功后要执行的函数【底层会自动将函数保存到对象的 callback 属性上】
    echo "process 出来了".PHP_EOL;
}, false);


$process->start();

Swoole\Process::wait();//等待子进程执行完之后，主进程再结束；回收结束运行的子进程

//用来替代PHP的pcntl。需要注意Process进程在系统是非常昂贵的资源，消耗很大

//应用场景：消息队列、多进程爬虫
















