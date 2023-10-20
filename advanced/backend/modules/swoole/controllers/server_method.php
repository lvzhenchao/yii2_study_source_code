<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的

//    echo "hello world";
    //$serv->send($fd, "hello ".$data);//向客户端发送数据

    echo "worker_id：".$serv->worker_id;
    $serv->sendMessage("hello 啊", $serv->worker_id);
});

$server->on("workerstart", function ($serv, $workerId) {//在Worker 进程 / Task 进程 启动时发生
    echo "worker_id...".$workerId.PHP_EOL;
});

//当工作进程收到由 $server->sendMessage() 发送的 unixSocket 消息时会触发 onPipeMessage 事件。
//worker/task 进程都可能会触发 onPipeMessage 事件
$server->on("pipemessage", function ($serv, $src_worker_id,$message) {
    echo "pipemessage...worker_id...".$serv->worker_id."...src_worker_id...".$src_worker_id."...message...".$message.PHP_EOL;
});

$server->set([
    "worker_num" => 3, //设置worker进程数
    //"task_worker_num"=>3, //task进程数：务必要注册onTask || onFinish2个事件回调函数
]);

$server->start();

//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

//send()
//sendfile()
//exist($fd)
//sendMessage() 向任意worker进程或者task进程发送消息

