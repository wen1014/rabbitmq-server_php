<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('pd_file_create');
$a->receive(function($result){
    var_dump($result);
    return true;
});