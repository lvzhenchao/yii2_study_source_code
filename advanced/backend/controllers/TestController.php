<?php

namespace backend\controllers;

class TestController extends \yii\web\Controller
{
    //默认的操作方法在\yii\base\Controller：public $defaultAction = 'index';
    public $defaultAction = 'index2';

    //http://yii2_study.com/backend/web/index.php?r=test/sms
    public function actions()
    {
        //控制器中覆盖yii\base\Controller::actions()方法
        return [
            //名称key随便起

//            'sms' => 'backend\actions\SmsSendAction',

            'sms' => [
                'class' => 'backend\actions\SmsSendAction',
                'param1' => \Yii::$app->request->get('tel'),
                'param2' => \Yii::$app->request->get('name'),
                'param3' => '!!!',
              ],
        ];
    }

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

    public function actionSms($telphone)
    {
        echo "内联动作发短信给："+$telphone;
    }

}
