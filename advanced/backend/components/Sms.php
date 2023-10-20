<?php
namespace backend\components;

use yii\base\Component;

class Sms extends Component
{
    public function send($tel)
    {
        echo '给'.$tel.'成功发送了短信';
    }
}