<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $controller, $action) {
        // Remove trailing slash from path to standardize
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        // Convert path to regex (for dynamic routes like /users/:id)
        $pathRegex = preg_replace('/:[a-zA-Z0-9_]+/', '([a-zA-Z0-9_-]+)', $path);
        
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => "#^" . $pathRegex . "$#",
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($path, $controller, $action) {
        $this->add('GET', $path, $controller, $action);
    }

    public function post($path, $controller, $action) {
        $this->add('POST', $path, $controller, $action);
    }

    public function dispatch($method, $uri) {
        // Remove trailing slash from uri to standardize
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        // Handle root cases where uri might be empty
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['pattern'], $uri, $matches)) {
                
                // Remove the full match
                array_shift($matches);
                
                $controllerName = "App\\Controllers\\" . $route['controller'];
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    $action = $route['action'];
                    
                    if (method_exists($controller, $action)) {
                        // Call the method with captured params from URL
                        call_user_func_array([$controller, $action], $matches);
                        return;
                    }
                }
            }
        }
        
        // If no route matched
        http_response_code(404);
        echo "404 Not Found (URI: " . htmlspecialchars($uri) . ")";
    }
}
