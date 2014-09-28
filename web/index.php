<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new \Miiof\Application(__DIR__ . '/..');
$app->configure(__DIR__ . '/../app/config/config.yml');

$app->register(new Predis\Silex\ClientServiceProvider(), [
    'predis.parameters' => 'tcp://127.0.0.1:6379',
    'predis.options'    => [
        'prefix'  => 'miiof:',
        'profile' => '3.0',
    ],
]);

//$accessToken = "needs to be received from oauth majigga and all this and that";
//$app['dropbox.client'] = new \Dropbox\Client($accessToken, "Miiof");

$app->run();
