<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->on("receive", function ($serv){//这个方法是TCP协议运行时必须有的
//    $serv->task("send mail");
    $serv->task("send mail", -1, function ($serv, $task_id, $res) {
        echo "task_res——task_id...".$task_id.PHP_EOL;
        echo "task_res...".$res.PHP_EOL;
    });
    echo "login...".PHP_EOL;
});

$server->on("start", function ($serv) {//最开始调用的回调方法
//    echo "master_pid：".$serv->master_pid.PHP_EOL;
//    echo "manager_pid：".$serv->manager_pid.PHP_EOL;
});

$server->on("workerstart", function ($serv, $worker_id) {//WorkerStart/onStart 是并发执行的，没有先后顺序
//    if ($serv->taskworker) {
//        echo "task_pid：".$serv->worker_pid.PHP_EOL;
//        echo "task_id......".$worker_id.PHP_EOL;
//    } else {
//        echo "worker_pid：".$serv->worker_pid.PHP_EOL;
//        echo "worker_id......".$worker_id.PHP_EOL;
//    }

});

$server->on("connect", function ($serv,$fd,$reactorId) {//新的连接进入时，在 worker 进程中回调
    echo "fd:".$fd. " reactorId:".$reactorId.PHP_EOL;
});

$server->set([
    "task_worker_num"=>3, //task进程数：务必要注册onTask || onFinish2个事件回调函数
]);

$server->on("task", function ($serv,$task_id,$src_worker_id, $data){
    sleep(5);
    echo "task....task_id:".$task_id.PHP_EOL;
    echo "task....src_worker_id:".$src_worker_id.PHP_EOL;
    echo "task....data:".$data.PHP_EOL;

    return "success";
});

//$server->on("finish", function ($serv,$task_id,$res){
//    echo "完事儿了".$res;
//});



$server->start();


//