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
        Yii::$app->cache->set($action->uniqueId.'_time', "当前脚本: '{$action->uniqueId}' 消耗时间大小为： $time 秒.");
//        pr("当前脚本: '{$action->uniqueId}' 消耗时间大小为： $time 秒.");
//        echo "<script>alert(\"当前脚本: '{$action->uniqueId}' 消耗时间大小为： $time 秒.\");</script>";
        return parent::afterAction($action, $result);
    }

}