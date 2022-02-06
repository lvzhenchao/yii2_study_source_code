<?php
namespace backend\modules\app\controllers;

use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `app` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'backend\modules\app\models\User';

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
//                    'application/xml' => Response::FORMAT_XML,
                ],
            ],

        ];
    }
    public function actions()
    {
        $actions =  parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $data = $modelClass::find()->all();

        return $data;
    }

}
