<?php
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../src/services.php';
require __DIR__ . '/../src/routes.php';

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'dbname' => 'url_shorter',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8'
    ]
]);

$app->register(new ServiceControllerServiceProvider());

$app->before(function(Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});
