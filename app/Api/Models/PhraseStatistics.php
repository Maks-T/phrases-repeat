<?php

declare(strict_types=1);

namespace App\Api\Models;

use PDO;
use PDOException;
use App\Api\DB\Database;

class PhraseStatistics
{
    // Получение фраз для повторения с учетом статистики
    public static function getPhrasesForReview(int $userId): array
    {
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare(
                'SELECT p.*, ps.repetitions, ps.correct, ps.incorrect, ps.last_reviewed 
                 FROM phrases p
                 LEFT JOIN phrase_statistics ps ON p.id = ps.phrase_id 
                 WHERE p.user_id = :user_id 
                 ORDER BY ps.last_reviewed ASC 
                 LIMIT 10'
            );
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

    // Обновление статистики фразы
    public static function updateStatistics(int $phraseId, int $userId, bool $isCorrect): bool
    {
        try {
            $db = Database::getInstance();

            // Проверка, существует ли запись в статистике
            $stmt = $db->prepare('SELECT * FROM phrase_statistics WHERE phrase_id = :phrase_id AND user_id = :user_id');
            $stmt->bindParam(':phrase_id', $phraseId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $statistics = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($statistics) {
                // Обновление существующей записи
                $stmt = $db->prepare(
                    'UPDATE phrase_statistics 
                     SET repetitions = repetitions + 1, 
                         correct = correct + :correct, 
                         incorrect = incorrect + :incorrect, 
                         last_reviewed = CURRENT_TIMESTAMP 
                     WHERE phrase_id = :phrase_id AND user_id = :user_id'
                );
                $stmt->bindValue(':correct', $isCorrect ? 1 : 0, PDO::PARAM_INT);
                $stmt->bindValue(':incorrect', $isCorrect ? 0 : 1, PDO::PARAM_INT);
                $stmt->bindParam(':phrase_id', $phraseId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            } else {
                // Вставка новой записи
                $stmt = $db->prepare(
                    'INSERT INTO phrase_statistics (phrase_id, user_id, repetitions, correct, incorrect, last_reviewed) 
                     VALUES (:phrase_id, :user_id, 1, :correct, :incorrect, CURRENT_TIMESTAMP)'
                );
                $stmt->bindValue(':correct', $isCorrect ? 1 : 0, PDO::PARAM_INT);
                $stmt->bindValue(':incorrect', $isCorrect ? 0 : 1, PDO::PARAM_INT);
                $stmt->bindParam(':phrase_id', $phraseId, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            return false;
        }
    }
}
