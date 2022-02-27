<?php
namespace backend\modules\event\models;

use yii\db\ActiveRecord;

class Gov extends ActiveRecord {

    //$event的形参，它会将本次事件一些必要的参数传递给每个观察者的方法
    public static function notify($event){
        pr("政府监听");
    }
}