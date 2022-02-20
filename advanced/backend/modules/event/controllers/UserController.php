<?php

namespace backend\modules\event\controllers;

use backend\modules\event\events\UserLoginEvent;
use yii\web\Controller;


class UserController extends Controller
{

    //事件绑定：让三个观察者订阅登录主题，在登录之前完成；
    //可以在构造函数里面做这个事情

    //定义事件名字
    const EVENT_USER_LOGIN = 'user_login';

    //构造函数里面绑定事件；任何前置方法都可以去使用绑定
    public function __construct()
    {
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\events\models\OLog', 'add']);
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\events\models\Admin', 'sendMail']);
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\events\models\User', 'notifyFirend']);
    }

    public function actionIndex()
    {
        //这里有些代码
//        \Yii::$app->user->login($user);
        echo "用户1正在登录...";
        sleep(5);

        //登录后去触发事件：可以去做相应的判断，对于登录成功与否
        //为了明确是哪个会员登陆了、如何发邮件；需要给事件的处理者【观察者】传递相应的参数
        //trigger的第二个参数：可以传递参数

        $event = new UserLoginEvent();
        $event->userId = 1;//$user->id;

        $this->trigger(self::EVENT_USER_LOGIN, $event);

        //登录后做其他的事情
    }
}

























