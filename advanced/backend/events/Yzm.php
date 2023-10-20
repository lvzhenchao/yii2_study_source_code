<?php
namespace backend\events;

//不需要继承任何基类

class Yzm {

    public function event1($event)
    {
//        prd($event);
        pr($event->data);
        echo '我是yzm的event1方法;';
        echo '<br>';

//        $event->handled = true;//不处理后面的方法
    }

    public  static function event2()
    {
        echo '我是yzm的event2静态方法;';
        echo '<br>';
    }
}