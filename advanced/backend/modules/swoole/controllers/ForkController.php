<?php

namespace backend\modules\swoole\controllers;

use yii\web\Controller;

/**
 * Default controller for the `swoole` module
 */
class ForkController extends Controller
{

    public function actionFork()
    {
        $pid = pcntl_fork();
        
        if ($pid == -1) {
            die("could not fork");
        } elseif($pid == 0) {
            prd("我是子进程id：".getmypid());
        } else {
            prd("我是父级进程".$pid);
        }
    }
}
