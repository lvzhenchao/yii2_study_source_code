<?php

$process = new Swoole\Process(function ($pro){//子进程创建成功后要执行的函数【底层会自动将函数保存到对象的 callback 属性上】

    echo $pro->exec('/usr/bin/php',['/home/mycode/yii2_study_source_code/advanced/backend/modules/swoole/controllers/echo.php']);

}, false);//这个参数决定是否能打印出来；默认false可以echo出来；ture需要方法：$process->read()
//上面都是子进程

//下面都是主进程
$process->start();



swoole_process::wait();//等待子进程执行完之后，主进程再结束；回收结束运行的子进程

//Swoole\Process->exec(string $execfile, array $args);执行一个外部程序

//查看进程：ps -ef | grep php
//root     32371 17833  0 21:38 pts/0    00:00:00 master process:php
//root     32372 32371  0 21:38 pts/0    00:00:00 child process:php

















