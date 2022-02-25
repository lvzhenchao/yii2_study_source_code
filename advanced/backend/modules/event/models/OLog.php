<?php
namespace backend\modules\event\models;

class OLog {

    //$event的形参，它会将本次事件一些必要的参数传递给每个观察者的方法
    public static function add($event){
        echo "我记录了一条登录记录";
    }
}