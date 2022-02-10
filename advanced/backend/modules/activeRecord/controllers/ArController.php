<?php

namespace backend\modules\ActiveRecord\controllers;

use yii\web\Controller;


class ArController extends Controller
{

    public function actionIndex()
    {
        prd("Ar控制器“");
        return $this->render('index');
    }
}
