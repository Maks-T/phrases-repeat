<?php

declare(strict_types=1);

namespace App\Api;
class Router
{
    protected array $routes = [];
    protected string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = trim($baseUrl, '/');
    }

    // Метод для регистрации маршрутов
    public function register(string $method, string $path, string $controller): void
    {
        $method = strtolower($method);
        $path = trim($path, '/');
        $this->routes[$method][$path] = $controller;
    }

    // Метод для обработки входящего запроса
    public function dispatch(string $requestUri, string $requestMethod, array $request = []): void
    {

        $requestMethod = strtolower($requestMethod);
        $requestUri = trim($requestUri, '/');
        $requestUri = trim(str_ireplace($this->baseUrl, '', $requestUri), '/');

        // Разделить URI и строку запроса
        $uriParts = explode('?', $requestUri, 2);
        $path = trim($uriParts[0], '/');
        $queryString = $uriParts[1] ?? '';

        // Разобрать строку запроса и добавить параметры в $request
        parse_str($queryString, $queryParams);
        $request = array_merge($request, $queryParams);

        if (isset($this->routes[$requestMethod][$path])) {
            $controller = $this->routes[$requestMethod][$path];
            $this->callControllerMethod($controller, $requestMethod, $request);
        } else {
            http_response_code(404);
            header("Content-Type: application/json");
            echo json_encode(['status' => 'error', 'message' => 'Not Found']);
        }
    }

    // Метод для вызова нужного метода у контроллера
    protected function callControllerMethod(string $controller, string $method, array $request): void
    {
        $controllerInstance = new $controller();

        if (method_exists($controllerInstance, $method)) {
            $controllerInstance->$method($request);
        } else {
            $controllerInstance->methodNotAllowed();
        }
    }
}
