<?php
namespace Core;

class Router {
    protected $routes = [];

    public function get($uri, $controllerAction) { $this->routes['GET'][$uri] = $controllerAction; }
    public function post($uri, $controllerAction) { $this->routes['POST'][$uri] = $controllerAction; }

    public function dispatch($url) {
        $url = '/' . trim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $route => $action) {
            // Chuyển đổi {slug} thành regex ([a-zA-Z0-9-]+)
            $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_-]+)\}/', '([a-zA-Z0-9_-]+)', $route) . "$#";
            
            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Lấy các tham số biến (ví dụ: slug)
                
                $parts = explode('@', $action);
                $controllerName = "Controllers\\" . $parts[0];
                $methodName = $parts[1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    // Gọi method và truyền các tham số từ URL vào
                    return call_user_func_array([$controller, $methodName], $matches);
                }
            }
        }
        $this->handleNotFound();
    }

    private function handleNotFound() {
        http_response_code(404);
        echo "404 - Không tìm thấy trang!";
    }
}