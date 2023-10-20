<?php

$pid = pcntl_fork();

$str = 'lzc';

if ($pid > 0) {
    $str .= "123";
    echo $str.PHP_EOL;
} else {
    echo $str.PHP_EOL;
}

