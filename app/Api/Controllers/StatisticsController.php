<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Models\PhraseStatistics;
use App\Api\Models\Phrase;

class StatisticsController extends BaseController
{
    // Метод для получения фраз для повторения
    public function get(array $request): void
    {
        if (!isset($request['user_id'])) {
            $this->sendErrorResponse('User ID is required', 400);
            return;
        }

        $userId = (int) $request['user_id'];
        $phrases = PhraseStatistics::getPhrasesForReview($userId);

        if ($phrases) {
            $this->sendSuccessResponse($phrases);
        } else {
            $this->sendErrorResponse('No phrases found for review', 404);
        }
    }

    // Метод для обновления статистики после повторения
    public function post(array $request): void
    {
        if (!isset($request['phrase_id']) || !isset($request['user_id']) || !isset($request['is_correct'])) {
            $this->sendErrorResponse('Phrase ID, User ID, and result (is_correct) are required', 400);
            return;
        }

        $phraseId = (int) $request['phrase_id'];
        $userId = (int) $request['user_id'];
        $isCorrect = filter_var($request['is_correct'], FILTER_VALIDATE_BOOLEAN);

        $updated = PhraseStatistics::updateStatistics($phraseId, $userId, $isCorrect);

        if ($updated) {
            $this->sendSuccessResponse(null, 'Statistics updated successfully');
        } else {
            $this->sendErrorResponse('Failed to update statistics', 500);
        }
    }
}
