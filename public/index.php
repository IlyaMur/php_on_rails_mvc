<?php

declare(strict_types=1);

/**
 * Front Controller
 * 
 * PHP version 8.0
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Routing
 */

$router = new Ilyamur\PhpOnRails\Router();

$router->add(route: '', params: ['controller' => 'Home', 'action' => 'index']);
$router->add(roue: '{controller}/{action}');
$router->add(route: '{controller}/{id:\d+}/{action}');
$router->add(route: 'admin/{controller}/{action}', params: ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);
