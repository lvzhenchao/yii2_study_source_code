<?php 

$pid = pcntl_fork();

if ($pid > 0) {
    echo ("我是父进程执行得到的子进程号：".$pid).PHP_EOL;
} else {
    echo ("我是子进程执行得到的自己的进程号：".$pid).PHP_EOL;
}

