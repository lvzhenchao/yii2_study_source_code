<?php

$server = new Swoole\Server('0.0.0.0', 6666, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);

$server->set([
    "worker_num" => 2, //设置worker进程数
    "task_worker_num"=>2, //task进程数：务必要注册onTask || onFinish2个事件回调函数
]);

//$fd一个客户端链接会记录一个全局的唯一的文件描述符 1~16000000 用以区分客户端
$server->on("receive", function ($serv, $fd, $reactor_id, $data){//这个方法是TCP协议运行时必须有的

    //$serv->task("task data");//投递到2个task进程中的一个，发给ontask回调中

    $res = $serv->taskwaitMulti([11,22,33], 5);//多个任务并发执行的数量跟task_worker_num数量设置有关
    print_r($res);
    echo "login...".PHP_EOL;//即时返回
});

$server->on("task", function ($serv, $task_id, $src_worker_id, $data) {
    sleep(3);//模仿耗时任务
    echo "task..."."task_id...".$task_id."...".$data.PHP_EOL;

    return "成功".$data;//1、只要return 就会调取onfinish回调方法，
    //$serv->finish("成功");//2、使用finish方法触发onfinish回调方法

});

$server->on("finish", function ($server, $task_id, $data) {
    echo "finish...".$data.PHP_EOL;
});

$server->start();

//建议使用1方法
//1、在 onTask 函数中 return 字符串，表示将此内容返回给 worker 进程。worker 进程中会触发 onFinish 函数，表示投递的 task 已完成
//2、$serv->finish() 来触发 onFinish 函数，而无需再 return
//3、$serv->task($data,  -1, $finishCallback)里面的3个参数，第3个参数可以代替finish

//telnet 127.0.0.1 6666 模拟客户端向服务端发送数据
//task() 投递一个【异步任务】到 task_worker 池中。此函数是非阻塞的，执行完毕会立即返回
//taskwait($data, $timeout = 0.5, $dstWorkerId = -1) 同步等待方法，任务运行的时间只要超过了$timeout,就会直接返回结果
//taskWaitMulti(array $tasks, float $timeout = 0.5)并发执行多个 task 异步任务
////多个任务并发执行的数量跟task_worker_num数量设置有关
//taskCo()并发协程


