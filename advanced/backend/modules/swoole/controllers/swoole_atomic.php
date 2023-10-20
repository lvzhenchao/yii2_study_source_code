<?php

//$atomic = new Swoole\Atomic(22);
//
//$atomic->add(3);
//$atomic->sub(1);
//
//
//echo $atomic->get().PHP_EOL;


$atomic = new Swoole\Atomic(0);

if (pcntl_fork() > 0) {
    echo 'master start'.PHP_EOL;
    $atomic->wait(5);
    echo 'master end'.PHP_EOL;
} else {
    echo 'child start'.PHP_EOL;

    $atomic->wakeup();
    echo 'child end'.PHP_EOL;
}



//原子性：互斥访问，同一时刻只能有一个线程对它操作
//可见性：一个线程对主内存的修改可以及时的被其他线程观察到
//有序性：一个线程观察其他的线程中的指令执行顺序，

