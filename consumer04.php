<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('user_list2');
$a->receive(function($result){
    var_dump($result);
    return true;
});