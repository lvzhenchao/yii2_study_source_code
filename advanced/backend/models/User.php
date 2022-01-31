<?php
namespace backend\models;

use yii\base\Model;

class User extends Model {
    public $nickname;
    public $age;

    public function attributeLabels()
    {
        return [
            'nickname' => '昵称',
        ];
    }

    public function rules()
    {
        return [
            [['nickname', 'age'], 'required'/*,'on'=>'register'*/],//场景写法1
//            ['age', 'in', 'range'=>[18, 35]]
        ];
    }

    //场景写法2
    public function scenarios()
    {
        return [
            'register' => ['username', 'age'],
        ];
    }
}