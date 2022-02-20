<?php

namespace backend\modules\event\controllers;

use yii\web\Controller;

/**
 * Default controller for the `event` module
 */
interface Observable {
    //添加/注册观察者
    public function attach(Observer $observer);
    //删除观察者
    public function detach(Observer $observer);
    //触发通知
    public function notify();
}

interface Observer {
    //接收到通知的处理方法
    public function update(Observable $observable);
}

class ObservableController extends Controller implements Observable
{

    public $observers = [];

    //添加观察者
    public function attach(Observer $observer)
    {
        $key = array_search($observer, $this->observers);
        if ($key == false) {
            $this->observers[] = $observer;
        }
    }

    //删除观察者
    public function detach(Observer $observer)
    {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    //通知观察者
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    //有新消息了，去通知给大家
    public function news()
    {
        $news = [1,2,3];
        if ($news) {
            $this->notify();
        }
    }
}
