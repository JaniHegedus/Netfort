<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', function($routes) {
    // Auth routes
    $routes->post('register', 'Api\Auth::register');
    $routes->post('login', 'Api\Auth::login');

    // News routes
    $routes->group('news', function($routes) {
        $routes->post('upload', 'Api\News::upload');
        $routes->get('get', 'Api\News::getNews'); // Corrected the method from 'upload' to 'getNews'
    });
});