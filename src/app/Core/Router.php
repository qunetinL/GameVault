<?php

namespace App\Core;

class Router
{
    protected $routes = [
        'get' => [],
        'post' => [],
        'put' => [],
        'delete' => []
    ];
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

    public function put($path, $callback, $middleware = [])
    {
        $this->routes['put'][$path] = $callback;
        $this->middleware['put'][$path] = $middleware;
    }

    public function delete($path, $callback, $middleware = [])
    {
        $this->routes['delete'][$path] = $callback;
        $this->middleware['delete'][$path] = $middleware;
    }

    public function resolve()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        // Support for method spoofing (e.g. via _method hidden input)
        if ($method === 'post' && isset($_POST['_method'])) {
            $method = strtolower($_POST['_method']);
        }

        // Remove query string
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        foreach ($this->routes[$method] as $route => $callback) {
            // Convert :id or {id} to regex
            $pattern = preg_replace('/:[a-zA-Z0-9]+/', '(?P<id>[a-zA-Z0-9]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $path, $matches)) {
                // Extract parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Execute Middleware
                $middlewares = $this->middleware[$method][$route] ?? [];
                foreach ($middlewares as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle();
                }

                if (is_string($callback)) {
                    $parts = explode('@', $callback);
                    $controllerName = "\\App\\Controllers\\" . $parts[0];
                    $action = $parts[1];

                    $controller = new $controllerName();
                    return $controller->$action(...array_values($params));
                }

                if (is_array($callback)) {
                    $controller = new $callback[0]();
                    return $controller->{$callback[1]}(...array_values($params));
                }

                return call_user_func($callback, ...array_values($params));
            }
        }

        http_response_code(404);
        if (strpos($path, '/api/') === 0) {
            header('Content-Type: application/json');
            return json_encode(['error' => 'Route not found']);
        }
        return "404 - Page non trouvée";
    }
}
