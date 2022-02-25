<?php
namespace backend\modules\event\models;

class User {

    //$event的形参，它会将本次事件一些必要的参数传递给每个观察者的方法
    public static function notifyFriend($event){
        $userID = $event->userId;
        pr("告诉了朋友们我:".$userID."登录了");
    }
}