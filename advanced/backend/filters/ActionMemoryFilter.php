<?php
namespace backend\filters;

use Yii;
use yii\base\ActionFilter;

class ActionMemoryFilter extends ActionFilter
{
    private $_startMemory;

    public function beforeAction($action)
    {
        $this->_startMemory = memory_get_usage();
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $memory = memory_get_usage() - $this->_startMemory;
        $memory = !empty($memory) ? $memory/1024/1024 : 0;
        Yii::$app->cache->set($action->uniqueId.'_memory', "当前脚本: '{$action->uniqueId}' 消耗内存大小为： $memory MB.");
//        pr("当前脚本: '{$action->uniqueId}' 消耗内存大小为： $memory MB.");
//        echo "<script>alert(\"当前脚本: '{$action->uniqueId}' 消耗内存大小为： $memory MB.\");</script>";
        return parent::afterAction($action, $result);
    }

}