<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%yzm_order}}".
 *
 * @property int $id
 * @property string $ordernum 订单号
 * @property int $addtime 添加时间
 * @property int $paytime 支付时间
 * @property int $userid 支付用户id
 * @property float $money
 */
class YzmOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%yzm_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ordernum', 'addtime', 'paytime', 'userid', 'money'], 'required'],
            [['addtime', 'paytime', 'userid'], 'integer'],
            [['money'], 'number'],
            [['ordernum'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ordernum' => 'Ordernum',//订单号
            'addtime' => 'Addtime',
            'paytime' => 'Paytime',
            'userid' => 'Userid',
            'money' => 'Money',
        ];
    }

    //数据格式转换；不好用
//    public function getAddtimeText()
//    {
//        return date('Y-m-d', $this->addtime);
//    }
    //数据格式转换；不好用
    public function getAddtime()
    {
        return date('Y-m-d', $this->addtime);
    }

    //    魔术方法；不好用
    public function __get($name)
    {
        if (in_array($name, ['addtime', 'paytime'])) {
            return date('Y-m-d', $this->$name);
        }

        return false;

    }

}
