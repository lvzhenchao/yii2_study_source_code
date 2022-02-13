<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $address
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required', 'on' => 'login'],//登录场景下
            [['user_id'], 'integer', ],//登录场景下
            [['address'], 'string', 'max' => 255],
            [['address'], 'string', 'on'=>'reg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'address' => '地址',
        ];
    }
}
