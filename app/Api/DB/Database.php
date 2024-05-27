<?php

declare(strict_types=1);

namespace App\Api\DB;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    // Закрытый конструктор, чтобы предотвратить создание объекта напрямую
    private function __construct() {}

    // Метод для получения экземпляра базы данных (Singleton)
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=localhost;dbname=f0985257_db';
            $username = 'f0985257_root';
            $password = 'Karina7478856';

            try {
                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // Обработка ошибки подключения к базе данных
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$instance;
    }

    // Клонирование и десериализация запрещены для Singleton
    private function __clone() {}

    private function __wakeup() {}
}