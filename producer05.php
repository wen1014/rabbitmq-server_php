<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('system_log');
$a->send([
    'data'=>rand().'user模块的警告日志'
],'userModule.warning');
