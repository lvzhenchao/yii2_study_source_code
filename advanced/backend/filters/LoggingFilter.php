<?php

namespace backend\filters;

use yii\base\ActionFilter;

class LoggingFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        // To do something
        printf('This is a logging for %s\beforeAction.%s', $this->getActionId($action), PHP_EOL);

        return true;
    }

    public function afterAction($action, $result)
    {
        parent::afterAction($action, $result);

        // To do something
        printf('This is a logging for %s\afterAction.%s', $this->getActionId($action), PHP_EOL);

        return true;
    }
}