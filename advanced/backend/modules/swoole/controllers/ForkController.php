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

        if ($pid == -1) {
            die("could not fork");
        } elseif($pid == 0) {//在子进程内返回0
            prd("我是子进程id：".getmypid());
        } else {//在父进程内，返回子进程号 大于0
            prd("我也是子进程".$pid);
        }
    }

    //进程间的的通信
    public function actionCommun()
    {
        $a = "lzc";
        $pid = pcntl_fork();

        if ($pid > 0) {
            $a .= "zhh";
            prd($a);
        } else {
            prd($a);
        }
    }
}
