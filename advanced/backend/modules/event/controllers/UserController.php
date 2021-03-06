<?php

namespace backend\modules\event\controllers;

use backend\modules\event\events\UserLoginEvent;
use backend\modules\event\models\Admin;
use yii\web\Controller;
use yii\web\User;

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
//        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\OLog', 'add']);
        //使用匿名函数记录到框架库中
        $this->on(self::EVENT_USER_LOGIN, function ($event) {
                            $time = date("Y-m-d H:i:s");
// dd($event);
// backend\modules\event\events\UserLoginEvent Object
// (
//    [userId] => 111
//    [name] => user_login
//    [sender] =>  backend\modules\event\controllers\UserController Object（内容）
//    [handled] => false
//    [data] => null
//  )
                            pr("有人在{$time}登陆了");
//            $event->handled = true;
                        });

        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\Admin', 'sendMail']);

        //单独发给摸个管理员
//        $admin = Admin::findOne(1);
//        $this->on(self::EVENT_USER_LOGIN, [$admin, 'sendMail']);
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\User', 'notifyFriend']);

        //on的第四个参数：是否将追加的时间放到末尾执行，默认是
        $this->on(self::EVENT_USER_LOGIN, ['backend\modules\event\models\Gov', 'notify'], null, false);

        //也可以绑定 yii\web\User里面的事件
//        $this->on(User::EVENT_AFTER_LOGIN, ['backend\modules\event\models\OLog', 'notifyFriend']);
//        $this->on(User::EVENT_AFTER_LOGIN, ['backend\modules\event\models\Admin', 'notifyFriend']);
//        $this->on(User::EVENT_AFTER_LOGIN, ['backend\modules\event\models\User', 'notifyFriend']);

        return parent::__construct($id, $module, $config = []);
    }

    public function actionIndex()
    {

        echo "登录成功...";
        //触发事件
        //如何确定给谁发短信呢
        $event = new UserLoginEvent();
        $event->userId = 111;
// dd($event);
// backend\modules\event\events\UserLoginEvent Object
// (
//    [userId] => 111
//    [name] => NULL
//    [sender] => NULL
//    [handled] => false
//    [data] => NULL
//  )
        $this->trigger(self::EVENT_USER_LOGIN, $event);


        //这样写
//        $this->trigger(self::EVENT_USER_LOGIN, new UserLoginEvent(['userId' => 22]));
    }
}
