<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\YzmOrder;
use Yii;

class TestController extends \yii\web\Controller
{
    //默认的操作方法在\yii\base\Controller：public $defaultAction = 'index';
//    public $defaultAction = 'index5';

    public $title = "标题";

//    public $layout = 'common';
//    public $layout = '@backend/template/layouts/common';


    //独立方法
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

    public function actionIndex5()
    {
//        echo \Yii::$app->viewPath;die;
        return $this->render('index5');
    }

    public function actionIndex7()
    {
//        echo "<pre>";
//        print_r($this->view->theme->basePath);
        return $this->render('index7');
    }

    public function actionIndex8()
    {
        return $this->render('index8');
    }

    public function actionIndex9()
    {
//        die(\Yii::getAlias('@web'));
//        die(\Yii::getAlias('@webroot'));
        return $this->render('index9');
    }

    public function actionIndex10()
    {
        $user = new User();
        $user->nickname = "小明";
        $user->age = 15;
//        echo $user['nickname'];
//        echo $user->nickname;

//        echo $user->getAttributeLabel('nickname');
        echo "<pre>";
//        print_r($user);
        $scenarios = $user->scenarios();
        print_r($scenarios);

    }

    public function actionIndex11()
    {
        $order = new YzmOrder();
//        $order->ordernum = time().rand(10,99);
//        $order->addtime = time();
//        $order->paytime = time()+100;
//        $order->userid = 1;
//        $order->money = 10.21;
//        prd($order->save());

        $red = $order::findOne(1);
        prd($red);
    }


    public function actionIndex14()
    {
        echo Yii::t('app', 'lzc');
    }

}
