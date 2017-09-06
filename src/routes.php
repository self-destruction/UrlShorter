<?php

$routes = $app['controllers_factory'];

$routes->post('/users', 'users.controller:register');
$routes->get('/users/me', 'users.controller:getUser');

$routes->post('/users/me/shorten_urls', 'urls.controller:createShortenUrl');
$routes->get('/users/me/shorten_urls', 'urls.controller:getAllUsersShortenUrls');
$routes->get('/users/me/shorten_urls/{hash}', 'urls.controller:getUsersShortenUrl');
$routes->delete('/users/me/shorten_urls/{hash}', 'urls.controller:deleteUsersShortenUrl');

$routes->get('/users/me/shorten_urls/{hash}/referer', 'urls.controller:getTopReferers');
$routes->get('/users/me/shorten_urls/{hash}/{format}', 'urls.controller:getCountOfTransitions');

$routes->get('/shorten_urls/{hash}', 'urls.controller:getRedirect');

$app->mount('/api/v1', $routes);