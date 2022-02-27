<?php
namespace backend\modules\event\models;

use backend\modules\event\events\MsgAfterEvent;
use backend\modules\event\events\MsgBeforeEvent;

class MsgHandler {

    /**
     * 处理发送消息前的事件
     * @param MsgBeforeEvent $event
     *
     */
    public function beforeSendMsg(MsgBeforeEvent $event){
        $content = "BERORE:".$event->date.',Msg:'.$event->data."\n";
        pr($content);
    }

    public function afterSendMsg(MsgAfterEvent $event){
        $content = "AFTER:".$event->from.'发送给:'.$event->to."内容如下：".$event->msg."\n";
        pr($content);
    }
}