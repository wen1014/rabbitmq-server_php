<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('user_list2');
$a->send([
    'data'=>rand().'用户2类型'
]);
