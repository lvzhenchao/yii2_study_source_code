<?php

namespace backend\controllers;

class TestController extends \yii\web\Controller
{
    //默认的操作方法在\yii\base\Controller：public $defaultAction = 'index';
    public $defaultAction = 'index2';

    public function actionIndex()
    {
        echo 111;
    }

    public function actionIndex1()
    {
        echo 222;
    }

    public function actionIndex2()
    {
        echo \Yii::$app->defaultRoute;
        echo "</br>";
        echo 333;
    }

}
