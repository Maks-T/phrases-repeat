<?php

declare(strict_types=1);
require_once $_SERVER["DOCUMENT_ROOT"] . "/../app/bootstrap.php";


use App\Api\Controllers\PhraseController;
use App\Api\Controllers\StatisticsController;
use App\Api\Router;


try {
    $router = new Router('/api/');

    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $request = json_decode(file_get_contents('php://input'), true) ?? [];

    // Регистрация маршрутов для PhraseController
    $router->register('GET', 'phrase', PhraseController::class);
    $router->register('POST', 'phrase', PhraseController::class);
    $router->register('PUT', 'phrase', PhraseController::class);
    $router->register('DELETE', 'phrase', PhraseController::class);

    // Регистрация маршрутов для StatisticsController
    $router->register('GET', 'statistics', StatisticsController::class);
    $router->register('POST', 'statistics', StatisticsController::class);

    $router->dispatch($requestUri, $requestMethod, $request);
} catch (\Throwable $e) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");

    header("Content-Type: application/json");

    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
