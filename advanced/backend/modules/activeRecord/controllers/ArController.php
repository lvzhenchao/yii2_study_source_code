<?php

namespace backend\modules\ActiveRecord\controllers;

use common\models\User;
use yii\web\Controller;


class ArController extends Controller
{

    public function actionIndex()
    {
        
        return $this->render('index');
    }
}
