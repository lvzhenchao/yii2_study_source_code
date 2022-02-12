<?php

namespace backend\modules\ActiveRecord\controllers;

use common\models\Address;
use common\models\User;
use yii\web\Controller;


class ArController extends Controller
{

    public function actionIndex()
    {
        $model = Address::findOne(1);


        pr($model->attributeLabels());//获取所有属性的标签
        pr($model->getAttributeLabel('address'));//获取某个属性的的标签
        pr($model->generateAttributeLabel('address11'));//生成某个属性的标签


        prd("----");
        return $this->render('index');
    }
}
