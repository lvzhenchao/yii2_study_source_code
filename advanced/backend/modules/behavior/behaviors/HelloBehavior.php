<?php
namespace backend\modules\behavior\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class HelloBehavior extends Behavior {
    public $name = "abei2017";

    //凡是新建会员的时候，都在其username加一个“+”号
    public function events(){
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
        ];
    }

    public function beforeInsert(){
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            $owner->username .= '+';
        }
    }
}