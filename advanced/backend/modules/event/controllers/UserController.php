<?php

namespace backend\modules\event\controllers;

use backend\modules\event\events\UserLoginEvent;
use yii\web\Controller;

/**
 * Default controller for the `event` module
 */
class UserController extends Controller
{

    const EVENT_USER_LOGIN = 'user_login';

    //事件绑定、观察者订阅主题
    public function __construct($id, $module, $config = [])
    {

        //绑定事件
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\OLog', 'add']);
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\Admin', 'sendMail']);
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\User', 'notifyFriend']);

        return parent::__construct($id, $module, $config = []);
    }

    public function actionIndex()
    {

        echo "登录成功";
        //触发事件
        //如何确定给谁发短信呢
//        $event = new UserLoginEvent();
//        $event->userId = 111;
//
//        $this->trigger(self::EVENT_USER_LOGIN, $event);


        //这样写
        $this->trigger(self::EVENT_USER_LOGIN, new UserLoginEvent(['userId' => 11111]));
    }
}
