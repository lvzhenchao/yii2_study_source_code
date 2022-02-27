<?php

namespace backend\modules\event\events;

use yii\base\Event;

class MsgAfterEvent extends Event {

    public $from = '';
    public $to = '';
    public $msg = '';
}