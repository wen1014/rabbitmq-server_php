<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('pd_file_log');
$a->receive(function($result){
    var_dump("收到了用户操作文件日志信息:{$result}");


    //返回true则消息确认 为false则处理失败
//    return true;
    return false;
});