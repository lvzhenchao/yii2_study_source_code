<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\YzmOrder;
use Yii;
use yii\helpers\Url;

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

        $res = $order::findOne(1)->addtime;
        dd($res);
    }


    public function actionIndex14()
    {
        echo Yii::t('app', 'lzc');
    }

    public function actionIndex15()
    {
        prd(Yii::$app->request);
    }

    public function actionIndex16()
    {
//        throw new \yii\web\NotFoundHttpException;
//        throw new \yii\web\ServerErrorHttpException;

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'msg' => '成功',
            'status' => 'ok',

        ];
    }

    public function actionIndex17()
    {
        echo Url::to(['index17'], true);
    }

    public function actionIndex21()
    {
//        prd(Yii::$app->session);
//        dd(Yii::$app->session->isActive);
//        dd(Yii::$app->session->getIsActive());

        $session = Yii::$app->session;

//        $session->setFlash('postDeleted', 'You have successfully deleted your post.');
//
//        echo $session->getFlash('postDeleted');
//
//        $result = $session->hasFlash('postDeleted');

        $session->addFlash('alerts', 'You have successfully deleted your post.');
        $session->addFlash('alerts', 'You have successfully added a new friend.');
        $session->addFlash('alerts', 'You are promoted.');

// 请求 #2
// $alerts 为名为'alerts'的flash信息，为数组格式
        $alerts = $session->getFlash('alerts');
        dd($alerts);

    }

    public function actionIndex22()
    {
//        $cookies = Yii::$app->request->cookies;
        
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => 'zh-CN',
            'expire' => time()+7*24*3600
        ]));
    }

}