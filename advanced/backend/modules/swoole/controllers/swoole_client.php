<?php

$client = new Swoole\Client(SWOOLE_SOCK_TCP);

$client->connect('0.0.0.0', 6666);

$client->send("我是客户端");

