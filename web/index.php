<?php
require __DIR__ . '/../vendor/autoload.php';
use \Dropbox as dbx;

$app = new \Miiof\Application(__DIR__ . '/..');
$app->configure(__DIR__ . '/../app/config/config.yml');

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Predis\Silex\ClientServiceProvider(), [
    'predis.parameters' => 'tcp://127.0.0.1:6379',
    'predis.options'    => [
        'prefix'  => 'miiof:',
        'profile' => '3.0',
    ],
]);

$app['session']->set('test', 1);
$csrfTokenStore = new dbx\ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
$appInfo = dbx\AppInfo::loadFromJsonFile($app['dropbox.key_location']);

$app['dropbox.appInfo'] = $appInfo;
$app['dropbox.webAuth'] = new dbx\WebAuth($app['dropbox.appInfo'], "Miiof", "https://miiof.smellynose.com/dropbox/finish", $csrfTokenStore);


$app->run();
