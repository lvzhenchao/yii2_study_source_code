<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->on("receive", function ($params){//这个方法是TCP协议运行时必须有的
    //var_dump($params->setting);//另开一个xshell telnet 127.0.0.1 6666 随便输入点儿东西
    print_r($params->master_pid);
    print_r($params->manager_pid);
    print_r($params->worker_id);//多个里面的其中一个



});

$server->set([
    "worker_num" => 6, //设置worker进程数
    "reactor_num" => 3,//设置线程数量
    "task_worker_num"=>3, //task进程数：务必要注册onTask || onFinish2个事件回调函数
//    "daemonize"=>true,//守护进程  killall php
]);

$server->on("task", function (){

});

//netstat -nltp 端口号
//pstree -p 26452 查看是否是6个worker进程
// pstree -p 26823
//php(26823)─┬─php(26826)─┬─php(26830)
//           │            ├─php(26831)
//           │            ├─php(26832)
//           │            ├─php(26833)
//           │            ├─php(26834)
//           │            └─php(26835)
//           ├─{php}(26827)
//           ├─{php}(26828)
//           └─{php}(26829)

//$setting
//set()函数所设置的参数会保存到Server->$setting属性上
//
//$master_pid
//返回当前服务器主进程的PID。
//
//$manager_pid
//返回当前服务器管理进程的PID。
//
//$worker_id
//得到当前Worker进程的编号，包括Task进程。
//
//$worker_pid
//得到当前Worker进程的操作系统进程ID

//telnet 127.0.0.1 6666 运行完之后再去输入随意字符；相当于去请求了


$server->start();
