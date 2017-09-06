<?php 

use UrlShorter\Controller\UserController;
use UrlShorter\Repository\UserRepository;
use UrlShorter\Service\UserService;

use UrlShorter\Controller\UrlController;
use UrlShorter\Repository\UrlRepository;
use UrlShorter\Service\UrlService;

use UrlShorter\Controller\TransitionController;
use UrlShorter\Repository\TransitionRepository;
use UrlShorter\Service\TransitionService;

$app['users.controller'] = function ($app) {
    return new UserController($app['users.service']);
};

$app['users.service'] = function ($app) {
    return new UserService($app['users.repository']);
};

$app['users.repository'] = function ($app) {
    return new UserRepository($app['db'], $app['db.options']['dbname']);
};

$app['urls.controller'] = function ($app) {
    return new UrlController(
        $app['users.service'],
        $app['urls.service'],
        $app['transitions.service']
    );
};

$app['urls.service'] = function ($app) {
    return new UrlService($app['urls.repository']);
};

$app['urls.repository'] = function ($app) {
    return new UrlRepository($app['db'], $app['db.options']['dbname']);
};


$app['transitions.service'] = function ($app) {
    return new TransitionService($app['transitions.repository']);
};

$app['transitions.repository'] = function ($app) {
    return new TransitionRepository($app['db'], $app['db.options']['dbname']);
};