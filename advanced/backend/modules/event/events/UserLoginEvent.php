<?php

namespace backend\modules\event\events;

use yii\base\Event;

class UserLoginEvent extends Event {

    public $userId = 0;
}