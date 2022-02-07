<?php

namespace backend\modules\app\models;

use common\models\Address;
use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $admin
 * @property string $password
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin', 'password'], 'required'],
            [['admin'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'admin' => 'Admin',
            'password' => 'Password',
        ];
    }

    public function getAddress()
    {
        return $this->hasOne(Address::className(),['user_id' => 'id']);
    }

    //下面不是很推荐，因为fields里面存的是一些比较单纯的数据类型
//    public function fields()
//    {
//        $fields = parent::fields();
//
//        $fields['addr'] = 'address';//没有这个字段，所以会去找getAddress()
//
//        return $fields;
//    }


    //http://yii2_study.com/app/user/10?fields=id,admin&expand=address
    public function extraFields()
    {
        return [
            //第一种直接写
//            'address',

            //第二种
            'address' => function($model){
                return $model->address;
            }
        ];
    }



}
