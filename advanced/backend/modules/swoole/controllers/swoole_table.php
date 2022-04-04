<?php

$table = new Swoole\Table(1024);

$table->column('user_name', Swoole\Table::TYPE_STRING, 8);
$table->column('age', Swoole\Table::TYPE_INT, 1);
$table->column('fee', Swoole\Table::TYPE_FLOAT);

$table->create();

for ($i = 1; $i < 5; $i++) {
    $table->set($i, ['user_name' => 'lzc', 'age' => '12', 'fee' => 20.45]);
}

print_r($table->count());print_r(PHP_EOL);

//$table->set(1, ['user_name' => 'lzc', 'age' => '12', 'fee' => 20.45]);
//$table->incr(1, 'age', 23);
//$table->decr(1, 'age', 2);

//print_r($table->get(1));
//var_dump($table->exist(2));


