<?php
// define a working directory


define('APP_PATH', __DIR__); // PHP v5.3+
// load
require APP_PATH . '/vendor/autoload.php';

// Create Slim app and fetch DI Container
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

require __DIR__ . '/app/Dependencies.php';
require __DIR__ . '/app/Routes.php';

$app->run();
?>