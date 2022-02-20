<?php

namespace backend\modules\event\models;

//告诉了朋友们我登录了
class User  {
    static public function notifyFirend($event){
        $userId = $event->userId;
        echo "告诉朋友们我:".$userId."登陆了";
    }
}
