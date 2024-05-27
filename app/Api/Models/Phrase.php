<?php

declare(strict_types=1);

namespace App\Api\Models;

use PDO;
use PDOException;
use App\Api\DB\Database;

class Phrase
{
    // Получение всех фраз по ID пользователя
    public static function getAllByUserId(int $userId): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM phrases WHERE user_id = :user_id');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $phrases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Декодирование строки JSON обратно в массив
            foreach ($phrases as &$phrase) {
                $phrase['translate'] = json_decode($phrase['translate'], true);
            }

            return $phrases;
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return [];
        }
    }

    // Создание новой фразы
    public static function create(array $data): ?array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO phrases (user_id, phrase, translate) VALUES (:user_id, :phrase, :translate)');
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':phrase', $data['phrase'], PDO::PARAM_STR);
            $stmt->bindParam(':translate', $data['translate'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return self::getById((int)$db->lastInsertId());
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return null;
        }
    }

    // Обновление существующей фразы
    public static function update(int $id, array $data): ?array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('UPDATE phrases SET phrase = :phrase, translate = :translate WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':phrase', $data['phrase'], PDO::PARAM_STR);
            $stmt->bindParam(':translate', $data['translate'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return self::getById($id);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return null;
        }
    }

    // Удаление фразы
    public static function delete(int $id): bool
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('DELETE FROM phrases WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return false;
        }
    }

    // Получение фразы по ID
    public static function getById(int $id): ?array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM phrases WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return null;
        }
    }
}