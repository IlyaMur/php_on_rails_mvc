<?php

declare(strict_types=1);

/**
 * Front Controller
 * 
 * PHP version 8.0
 */

/**
 * Composer
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Routing
 */
$router = new Ilyamur\PhpOnRails\Service\Router();

// Add the routes
$router->add(route: '', params: ['controller' => 'Home', 'action' => 'index']);
$router->add(route: '{controller}/{action}');
$router->add(route: '{controller}/{id:\d+}/{action}');
$router->add(route: 'admin/{controller}/{action}', params: ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);
