<?php
namespace backend\modules\behavior\models;

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
            ]
        ];
    }
}