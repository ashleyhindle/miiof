<?php
require __DIR__ . '/../vendor/autoload.php';
use \Dropbox as dbx;

$app = new \Miiof\Application(__DIR__ . '/..');
$app->configure(__DIR__ . '/../app/config/config.yml');

$app->register(new Predis\Silex\ClientServiceProvider(), [
    'predis.parameters' => 'tcp://127.0.0.1:6379',
    'predis.options'    => [
        'prefix'  => 'miiof:',
        'profile' => '3.0',
    ],
]);

$appInfo = dbx\AppInfo::loadFromJsonFile($app['dropbox.key_location']);
$app['dropbox.appInfo'] = $appInfo;


$app->run();
