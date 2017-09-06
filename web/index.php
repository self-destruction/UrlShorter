<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

require __DIR__ . '/../src/app.php';

$app['debug'] = true;

$app->run();



