<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

//有新的连接进入时，在 worker 进程中回调
$server->on("connect", function ($serv, $fd, $reactor_id) {
//    print_r($serv->getClientInfo($fd));
//    print_r($serv->getClientList());
//    foreach ($serv->getClientList() as $v) {
//        $serv->send($v, "来了老弟...".PHP_EOL);
//    }

    $uid = mt_rand(1111, 9999);
    $serv->bind($fd, $uid);//绑定uid
    print_r($serv->getClientInfo($fd));//查看
});

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的
    if(trim($data) == "stop"){
        $serv->stop($serv->worker_id);
    }else{
        echo "data...".$data.PHP_EOL;
    }

});


$server->start();

//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据

//getClientInfo()
//getClientList()
//bind()

