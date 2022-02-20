<?php

namespace backend\modules\events\models;

//记录日志
class Olog  {
    static public function add($event){
        echo "我记录了一条登录日志";
    }
}
