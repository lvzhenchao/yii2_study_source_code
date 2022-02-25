<?php
namespace backend\modules\event\models;

class User {
    public static function notifyFriend($event){
        echo "告诉了朋友们我登录了";
    }
}