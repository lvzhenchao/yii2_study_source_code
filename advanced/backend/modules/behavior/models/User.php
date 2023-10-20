<?php
namespace backend\modules\behavior\models;

use backend\modules\behavior\behaviors\HelloBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends \common\models\User {

    public function behaviors()
    {
        return [
            [
                //自动更新数据表创建的时间和更新时间
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'update_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ]
            ],
            [
                //支持AR事件触发时自动修改它的属性
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['access_token']
                ],
                'value' => function($event) {
                    return md5($this->username);
                }
            ],
//            [
//                //美化url，优化url，语义化url
//                'class' => SluggableBehavior::className(),
//
//                //主要是为了一个AR对应数据表自动填充当前登录会员ID
//                'class' => BlameableBehavior::className(),
//
//                //自动转化模型属性格式的行为，对于类似MongoDB或Redis等模式的数据库来说非常有用
//                'class' => AttributeTypecastBehavior::className(),
//            ]

        //引入自定义的行为类
            [
                'class' => HelloBehavior::className()
            ],
        ];
    }
}