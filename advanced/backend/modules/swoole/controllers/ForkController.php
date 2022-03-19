<?php

namespace backend\modules\swoole\controllers;

use yii\web\Controller;

/**
 * Default controller for the `swoole` module
 */
class ForkController extends Controller
{

    //PHP实现多进程
    public function actionFork()
    {
        $pid = pcntl_fork();

        echo $pid.PHP_EOL;

        if ($pid > 0) {
            pr("我是父进程执行得到的子进程号：".$pid);
        } else {
            pr("我是子进程执行得到的自己的进程号：".$pid);
        }

        //单纯的执行 php fork.php
        //会输出：8198  父进程执行得到的子进程号
        //0 子进程得到的

        //因为fork是创建了一个子进程，父进程和子进程都从fork的位置开始向下继续执行，执行了两次
        //父进程执行的过程中，得到的fork返回值为子进程号
        //子进程执行的过程中，得到的fork返回值是0

        //PHP fork_commun.php 证明进程之间不会直接同行
        $pid = pcntl_fork();
        $str = 'lzc';
        if ($pid > 0) {
            $str .= "123";
            echo $str.PHP_EOL; //lzc123
        } else {
            echo $str.PHP_EOL; //lzc
        }


    }


}
