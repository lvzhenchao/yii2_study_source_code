<?php
namespace backend\modules\event\models;

class Admin {

    //$event的形参，它会将本次事件一些必要的参数传递给每个观察者的方法
    public static function sendMail($event){
        pr("我给管理员发了邮件");
    }
}