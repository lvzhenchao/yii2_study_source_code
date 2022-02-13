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

    public function actionAddress()
    {
        $model = new Address();
        $model->scenario = 'login';
//        prd($model->scenarios());
        if ($model->validate()) {
            prd('ok');
        } else {
            prd($model->errors);
        }
    }

    public function actionSave()
    {
        $model = new Address();
        $model->attributes;
        $model->save();
    }

    public function actionDirty()
    {
        $model = Address::findOne(1);
        $model->address = '上海';
        $model->user_id = 13;
        pr($model->getDirtyAttributes());

        //将数据库中表的设定默认值加载到相应的字段上
        $model = new Address();
        $model->loadDefaultValues();
        prd($model->attributes);
    }

    /**
     * 延迟加载和即时加载
     *
     */
    public function actionLoad()
    {
        //延迟加载
//        $user = User::findOne(1);
//        $a = $user->address;
//        $b = $user->address;

        //即时加载
        $users = User::find()->with('address')->all();
        foreach ($users as $user) {
            $og = $user->address;
        }
        //SELECT * FROM `user`
        //SELECT * FROM `address` WHERE `user_id` IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)

        return $this->render('index');
//        prd($a);

    }

    public function actionJoin()
    {
        $data = User::find()
            ->leftJoin('address',"user.id=address.user_id")
            ->with('addresss')
            ->asArray()->limit(3)->all();
        prd($data);

        $data1 = User::find()
            ->joinWith('addresss')
            ->asArray()->limit(3)->all();
        prd($data1);

    }

    public function actionVar()
    {
        $model = new User();
        pr($model->engName);

        echo "<hr/>";

        $model->engName = 'lzc';
        prd($model->engName);



    }

    public function actionRelation()
    {
        $user = User::findOne(1);
        pr($user->address);      //common\models\Address Object 是个 AR对象
        prd($user->getAddress());//yii\db\ActiveQuery Object

    }


}
