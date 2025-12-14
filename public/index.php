<?php

declare(strict_types=1);

require dirname(path: __DIR__) . '/vendor/autoload.php';

use Mini\Core\Router;

// Table des routes minimaliste
$routes = [
    ['GET', '/', [Mini\Controllers\HomeController::class, 'index']],
    ['GET', '/users', [Mini\Controllers\HomeController::class, 'users']],
    ['POST', '/users', [Mini\Controllers\HomeController::class, 'createUser']],
    ['GET', '/users/create', [Mini\Controllers\HomeController::class, 'showCreateUserForm']],
    ['GET', '/products', [Mini\Controllers\ProductController::class, 'listProducts']],
    ['GET', '/products/create', [Mini\Controllers\ProductController::class, 'showCreateProductForm']],
    ['POST', '/products', [Mini\Controllers\ProductController::class, 'createProduct']],
];
// Bootstrap du router
$router = new Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);