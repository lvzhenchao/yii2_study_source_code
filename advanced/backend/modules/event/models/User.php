<?php
namespace backend\modules\event\models;

class User {

    //$event的形参，它会将本次事件一些必要的参数传递给每个观察者的方法
    public static function notifyFriend($event){
        echo "告诉了朋友们我登录了";
    }
}