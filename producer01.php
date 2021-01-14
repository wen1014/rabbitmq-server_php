<?php
require_once __DIR__ . '/Rabbit.php';
$a = Rabbit::getInstance('pd_file_create');
$a->send([
    'data'=>rand()
]);
