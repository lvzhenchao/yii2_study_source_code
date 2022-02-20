<?php

namespace backend\modules\event\models;

//给管理员发邮件
class Admin {

    static public function sendMail($event){
        echo "我给管理员发了邮件";
    }

}
