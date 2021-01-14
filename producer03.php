<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('user_list1');
$a->send([
    'data'=>rand().'用户1类型'
]);
