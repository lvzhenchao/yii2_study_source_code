<?php
namespace backend\modules\app\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `app` module
 */
class AdminController extends ActiveController
{
    public $modelClass = 'backend\modules\app\models\User';

    //默认的方法都在独立方法里面
    public function actions()
    {
        $actions =  parent::actions(); // TODO: Change the autogenerated stub
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        return '我是app/admin的index';
    }
}
