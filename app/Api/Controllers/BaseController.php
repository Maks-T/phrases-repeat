<?php

declare(strict_types=1);

namespace App\Api\Controllers;

class BaseController
{
    // Метод для обработки GET-запросов
    public function get(array $request): void
    {
        $this->methodNotAllowed();
    }

    // Метод для обработки POST-запросов
    public function post(array $request): void
    {
        $this->methodNotAllowed();
    }

    // Метод для обработки PUT-запросов
    public function put(array $request): void
    {
        $this->methodNotAllowed();
    }

    // Метод для обработки DELETE-запросов
    public function delete(array $request): void
    {
        $this->methodNotAllowed();
    }

    // Метод для отправки успешного ответа 
    protected function sendSuccessResponse($data, $message = 'The data has been processed successfully', $status = 200): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode(['status' => 'success', 'message' => $message, 'data' => $data]);
    }

    // Метод для отправки ошибки
    protected function sendErrorResponse($message, $status = 400): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode(['status' => 'error', 'message' => $message]);
    }

    // Метод для обработки запроса к недоступному методу
    protected function methodNotAllowed(): void
    {
        $this->sendErrorResponse('Method Not Allowed', 405);
    }
}
