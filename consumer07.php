<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('dead.letter.pd_file_log');
$a->receive(function($result){
    var_dump("收到死信队列消息[文件日志创建失败]:{$result}");
    return true;
});