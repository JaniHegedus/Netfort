<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', function($routes) {
    $routes->post('register', 'Api\Auth::register');
    $routes->post('login', 'Api\Auth::login');
    $routes->post('news', 'Api\News::upload');
});