<?php

$process = new Swoole\Process(function ($pro){//子进程创建成功后要执行的函数【底层会自动将函数保存到对象的 callback 属性上】
    //echo "process 出来了".PHP_EOL;
    //$pro->write("process 出来了ye".PHP_EOL);

    $pro->name("child process:php");
    sleep(10);
    $pro->write("子进程pid...".$pro->pid.PHP_EOL);

}, true);//这个参数决定是否能打印出来；默认false可以echo出来；ture需要方法：$process->read()
//上面都是子进程

//下面都是主进程
$process->start();
$process->name("master process:php");

echo "pipe...".$process->read();

swoole_process::wait();//等待子进程执行完之后，主进程再结束；回收结束运行的子进程

//用来替代PHP的pcntl。需要注意Process进程在系统是非常昂贵的资源，消耗很大

//应用场景：消息队列、多进程爬虫

//查看进程：ps -ef | grep php
//root     32371 17833  0 21:38 pts/0    00:00:00 master process:php
//root     32372 32371  0 21:38 pts/0    00:00:00 child process:php

















