<?php

for ($i=0; $i<10; $i++) {

    $process = new Swoole\Process(function ($pro) use ($i){//子进程创建成功后要执行的函数【底层会自动将函数保存到对象的 callback 属性上】
        $pro->name("child process:php");
        sleep(1);//不是每次执行一秒，而是一次都输出了在这一秒
        echo $i."...Process".PHP_EOL;
        $pro->exit();
    }, false);//这个参数决定是否能打印出来；默认false可以echo出来；ture需要方法：$process->read()
    //上面都是子进程

    //下面都是主进程
    $process->start();
    $process->name("master process:php");

}
for ($i=0; $i<10; $i++) {
    print_r(swoole_process::wait());
//    swoole_process::wait();//必须wait，防止出现僵尸进程；回收子进程资源
}



//用来替代PHP的pcntl。需要注意Process进程在系统是非常昂贵的资源，消耗很大

//应用场景：消息队列、多进程爬虫

//查看进程：ps -ef | grep php
//root     32371 17833  0 21:38 pts/0    00:00:00 master process:php
//root     32372 32371  0 21:38 pts/0    00:00:00 child process:php

















