<?php

namespace backend\modules\behavior\controllers;

use backend\modules\behavior\behaviors\HelloBehavior;
use backend\modules\behavior\models\User;
use yii\web\Controller;

/**
 * Default controller for the `behavior` module
 */
class UserController extends Controller
{


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //查看一下库里的时间

        $model = new User();
        $model->username = 'lzc';
        if (!$model->save()) {
            prd($model->getErrors());
        }

        //这个有趣的touch函数，可以使用它将当前的时间戳给指定属性并保存到数据库
        pr($model->touch('do_time'));
        prd($model->toArray());

    }

    //使用自定义的行为类
    public function actionTest()
    {
//        $model = new User();
//        echo $model->name;

        //静态绑定
//        $model = new User();
//        $model->username = 'abei2017';
//        dd($model->save());

        //动态绑定
        $model = new User();

        $model->attachBehaviors([
            HelloBehavior::className()
        ]);

        $model->username = 'abei1982';
        dd($model->save());
    }
}
