<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $middleware = [];

    public function get($path, $callback, $middleware = [])
    {
        $this->routes['get'][$path] = $callback;
        $this->middleware['get'][$path] = $middleware;
    }

    public function post($path, $callback, $middleware = [])
    {
        $this->routes['post'][$path] = $callback;
        $this->middleware['post'][$path] = $middleware;
    }

    public function resolve()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        // Extraire le chemin sans les paramètres de requête (?...)
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            return "404 - Page non trouvée";
        }

        // Execute Middleware
        $middlewares = $this->middleware[$method][$path] ?? [];
        foreach ($middlewares as $middlewareClass) {
            $middleware = new $middlewareClass();
            $middleware->handle();
        }

        if (is_string($callback)) {
            $parts = explode('@', $callback);
            $controllerName = "\\App\\Controllers\\" . $parts[0];
            $action = $parts[1];

            $controller = new $controllerName();
            return $controller->$action();
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            return $controller->{$callback[1]}();
        }

        return call_user_func($callback);
    }
}
