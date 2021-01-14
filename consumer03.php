<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('user_list1');
$a->receive(function($result){
    var_dump($result);
    return true;
});