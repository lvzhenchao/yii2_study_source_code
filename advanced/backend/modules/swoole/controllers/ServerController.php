<?php

namespace backend\modules\swoole\controllers;

use yii\web\Controller;


class ServerController extends Controller
{

    public function actionIndex()
    {
        $server = new \swoole_server('0.0.0.0', 8888, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        $setting = [
            'worker_num'=>6,
            'reactor_num'=>4,
            'task_worker_num'=>3,
        ];

        $server->set($setting);

        $server->start();
    }


}
