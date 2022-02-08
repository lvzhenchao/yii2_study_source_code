<?php
namespace backend\modules\app\controllers;

use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
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
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        $behaviors['authenticator'] = [

            //'only' => ['say'],
//            'except' => ['say'],
//            'realm' => '',//域
            //第一种验证
//            'class' => HttpBasicAuth::className(),
//            'auth' => function($username, $password){
//                return User::find()->where(['admin'=>$username,'password'=>$password])->one();
//            },

            //第二种验证
//            'class' => QueryParamAuth::className(),
//            'tokenParam' => 'ac',//更换url的参数名称 http://yii2_study.com/app/user?ac=3166886

            //第三种验证
            'class' => HttpBearerAuth::className(),

        ];
        return $behaviors;
    }

//    public function actions()
//    {
//        $actions =  parent::actions();
//        unset($actions['index']);
//        return $actions;
//    }
//
//    public function actionIndex()
//    {
//        $modelClass = $this->modelClass;
//        $data = $modelClass::find()->all();
//
//        return $data;
//    }

    //测试http header登录验证 开始
//    public function actions()
//    {
//        $actions =  parent::actions();
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider_1'];
//        return $actions;
//    }
//
//    public function prepareDataProvider()
//    {
//        $params = \Yii::$app->request->queryParams;
//
//        $modelClass = $this->modelClass;
//
//        $query = $modelClass::find();
//
//        foreach ($params as $k => $v) {
//            if ($k == 'id') {
//                $query->andWhere(['id' => $v]);
//            }
//        }
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
//        return $dataProvider;
//    }
//
//    public function prepareDataProvider_1()
//    {
//        return \Yii::$app->user->identity;
//    }
//
//    public function actionSay()
//    {
//        return ["a" => "b"];
//    }

    //测试http header登录验证 结束



}
