<?php
namespace backend\filters;

use Yii;
use yii\base\ActionFilter;

class ActionTimeFilter extends ActionFilter
{
    private $_startTime;

    public function beforeAction($action)
    {
//        pr($action);
        $this->_startTime = microtime(true);
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
//        pr($action);
//        pr($result);
        $time = microtime(true) - $this->_startTime;
        prd("Action '{$action->uniqueId}' spent $time second.");
        return parent::afterAction($action, $result);
    }

}