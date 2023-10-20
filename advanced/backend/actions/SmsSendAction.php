<?php
namespace backend\actions;

use yii\base\Action;

class SmsSendAction extends Action
{

    //这里面的三个参数的值是通过控制器actions中配置而来的
    public $param1 = null;
    public $param2 = null;
    public $param3 = null;

//    public function run($telphone)
//    {
//        return "独立动作发短信给".$telphone;
//    }

    public function run()
    {
        return "独立动作发短信给".$this->param1.$this->param2.$this->param3;
    }
}