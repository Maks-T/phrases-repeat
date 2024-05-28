<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Api\Models\Phrase;

class PhraseController extends BaseController
{
    // Метод для обработки GET-запросов
    public function get(array $request): void
    {
        if (!isset($request['user_id'])) {
            $this->sendErrorResponse('User ID is required', 400);
            return;
        }

        $userId = (int) $request['user_id'];
        $phrases = Phrase::getAllByUserId($userId);

        if ($phrases) {
            $this->sendSuccessResponse($phrases);
        } else {
            $this->sendErrorResponse('No phrases found', 404);
        }
    }

    // Метод для обработки POST-запросов
    public function post(array $request): void
    {
        if (!isset($request['user_id']) || !isset($request['phrase']) || !isset($request['translate'])) {
            $this->sendErrorResponse('User ID, phrase, and translate are required', 400);
            return;
        }

        $userId = (int) $request['user_id'];
        $phrase = $request['phrase'];
        $translate = json_encode($request['translate']);
        $tr = $request['tr'];
        $comment = $request['comment'];

        $newPhrase = Phrase::create([
            'user_id' => $userId,
            'phrase' => $phrase,
            'translate' => $translate,
            'tr' => $tr,
            'comment' => $comment,
        ]);

        if ($newPhrase) {
            $this->sendSuccessResponse($newPhrase, 'Phrase created successfully', 201);
        } else {
            $this->sendErrorResponse('Failed to create phrase', 500);
        }
    }

    // Метод для обработки PUT-запросов
    public function put(array $request): void
    {
        if (!isset($request['id']) || !isset($request['phrase']) || !isset($request['translate'])) {
            $this->sendErrorResponse('Phrase ID, phrase, and translate are required', 400);
            return;
        }

        $phraseId = (int) $request['id'];
        $phrase = $request['phrase'];
        $translate = json_encode($request['translate']);

        $updatedPhrase = Phrase::update($phraseId, [
            'phrase' => $phrase,
            'translate' => $translate
        ]);

        if ($updatedPhrase) {
            $this->sendSuccessResponse($updatedPhrase, 'Phrase updated successfully');
        } else {
            $this->sendErrorResponse('Failed to update phrase', 500);
        }
    }

    // Метод для обработки DELETE-запросов
    public function delete(array $request): void
    {
        if (!isset($request['id'])) {
            $this->sendErrorResponse('Phrase ID is required', 400);
            return;
        }

        $phraseId = (int) $request['id'];
        $deleted = Phrase::delete($phraseId);

        if ($deleted) {
            $this->sendSuccessResponse(null, 'Phrase deleted successfully');
        } else {
            $this->sendErrorResponse('Failed to delete phrase', 500);
        }
    }
}