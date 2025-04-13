

<?php 

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch($method, $path) {
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            http_response_code(404);
            echo "404 - Not Found";
            return;
        } 

        if (is_callable($callback)) {
            call_user_func($callback);
        } elseif (is_array($callback)) {
            [$controller, $method] = $callback;
            require_once "controller/{$controller}.php";
            $controllerInstance = new $controller;
            $controllerInstance->$method();
        }

    }

}

?>