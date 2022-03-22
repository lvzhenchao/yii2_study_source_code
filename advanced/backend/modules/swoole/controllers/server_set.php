<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->on("receive", function (){//这个方法是TCP协议运行时必须有的
    echo "我是receive回调方法";
});

$server->set([
    "worker_num" => 6, //设置worker进程数
    "reactor_num" => 3,//设置线程数量
    "task_worker_num"=>3 //task进程数：务必要注册onTask || onFinish2个事件回调函数
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

//telnet 127.0.0.1 6666 运行完之后再去输入随意字符；相当于去请求了


$server->start();
