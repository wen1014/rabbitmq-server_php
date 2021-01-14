<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('system_log');
$a->send([
    'data'=>rand().'支付模块的严重错误日志'
],'payModule.error');
