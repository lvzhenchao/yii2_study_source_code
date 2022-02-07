<?php
namespace backend\filters;

use Yii;
use yii\base\ActionFilter;

class ActionTimeFilter extends ActionFilter
{
    private $_startTime;

    public function beforeAction($action)
    {
        $this->_startTime = microtime(true);
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $time = microtime(true) - $this->_startTime;
        pr("当前脚本: '{$action->uniqueId}' 消耗时间大小为： $time second.");
        return parent::afterAction($action, $result);
    }

}