<?php

namespace backend\modules\shop\controllers;

use yii\web\Controller;

class GoodsController extends Controller {

    public function actionIndex()
    {
        echo '我是shop模块下的goods控制器下面的控制器方法index';

        return $this->render('index');
    }
}