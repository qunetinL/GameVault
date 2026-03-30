<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();

// Définition des routes
// Définition des routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@loginView');
$router->post('/login', 'AuthController@login', [\App\Middleware\CSRFMiddleware::class, \App\Middleware\RateLimitMiddleware::class]);
$router->get('/register', 'AuthController@registerView');
$router->post('/register', 'AuthController@register', [\App\Middleware\CSRFMiddleware::class, \App\Middleware\RateLimitMiddleware::class]);
$router->get('/logout', 'AuthController@logout');

$router->get('/chat', 'ChatController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/collection', 'CollectionController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/sessions', 'SessionsController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/admin', 'AdminController@index', [\App\Middleware\AuthMiddleware::class, \App\Middleware\AdminMiddleware::class]);
$router->get('/dashboard', 'DashboardController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/game', 'GameController@index', [\App\Middleware\AuthMiddleware::class]);

$app = new App($router);
$app->run();