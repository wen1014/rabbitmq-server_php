<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('pd_file_log');
$a->send([
    'data'=>rand()."用户操作了A文件"
]);
