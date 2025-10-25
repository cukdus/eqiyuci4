<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Admin routes
$routes->group('admin', ['filter' => 'login'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});
