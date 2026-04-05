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
$router->get('/session/create', 'SessionsController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/session/create', 'SessionsController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/session/show', 'SessionsController@show', [\App\Middleware\AuthMiddleware::class]);
$router->post('/session/invite', 'SessionsController@invite', [\App\Middleware\AuthMiddleware::class]);
$router->post('/session/respond', 'SessionsController@respond', [\App\Middleware\AuthMiddleware::class]);
$router->post('/session/vote', 'SessionsController@vote', [\App\Middleware\AuthMiddleware::class]);

// API Endpoints
$router->get('/api/games', 'ApiController@getGames');
$router->get('/api/games/search', 'ApiController@searchGames');
$router->get('/api/games/:id', 'ApiController@getGame');
$router->post('/api/games', 'ApiController@createGame');
$router->put('/api/games/:id', 'ApiController@updateGame');
$router->delete('/api/games/:id', 'ApiController@deleteGame');
$router->get('/api/sessions', 'ApiController@getSessions');
$router->post('/api/messages', 'ApiController@sendMessage');
$router->get('/api/stats', 'ApiController@getStats');

$router->get('/admin', 'AdminController@index', [\App\Middleware\AuthMiddleware::class, \App\Middleware\AdminMiddleware::class]);
$router->get('/dashboard', 'DashboardController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/games', 'GameController@index', [\App\Middleware\AuthMiddleware::class]);
$router->get('/game', 'GameController@show', [\App\Middleware\AuthMiddleware::class]);
$router->get('/game/add', 'GameController@create', [\App\Middleware\AuthMiddleware::class]);
$router->post('/game/add', 'GameController@store', [\App\Middleware\AuthMiddleware::class]);
$router->get('/game/edit', 'GameController@edit', [\App\Middleware\AuthMiddleware::class]);
$router->post('/game/edit', 'GameController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('/game/delete', 'GameController@delete', [\App\Middleware\AuthMiddleware::class]);
$router->post('/game/toggle', 'GameController@toggleCollection', [\App\Middleware\AuthMiddleware::class]);

$app = new App($router);
$app->run();