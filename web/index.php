<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new \Miiof\Application(__DIR__ . '/..');
$app->configure(__DIR__ . '/../app/config/config.yml');

$app->run();
