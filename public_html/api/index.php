<?php

declare(strict_types=1);
require_once $_SERVER["DOCUMENT_ROOT"] . "/../app/bootstrap.php";


use App\Api\Controllers\PhraseController;
use App\Api\Router;


try {
    $router = new Router('/api/');

    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $request = json_decode(file_get_contents('php://input'), true) ?? [];

    $router->register('GET', 'phrase', PhraseController::class);

    $router->dispatch($requestUri, $requestMethod, $request);
} catch (\Throwable $e) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
