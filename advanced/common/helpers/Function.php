<?php

function pr($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

function prd($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    die;
}

function psq($var){
    echo "<pre>";
    print_r($var->createCommand()->sql);
    echo "</pre>";
    die;
}

function dd($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die;
}

function dv($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
}