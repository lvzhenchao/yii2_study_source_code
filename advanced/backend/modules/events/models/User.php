<?php

namespace backend\modules\events\models;

//告诉了朋友们我登录了
class Olog  {
    static public function add($event){
        echo "我记录了一条登录日志";
    }
}
