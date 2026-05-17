<?php

namespace Modules\TitanEchoAssist\Repositories;

use Modules\TitanEchoAssist\Models\Chatbot;

class ChatbotRepository
{
    public function modelClass(): string
    {
        return Chatbot::class;
    }

    public function query(): mixed
    {
        return Chatbot::query();
    }

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Chatbot
    {
        return Chatbot::create($attributes);
    }

    public function find(int|string $id): ?Chatbot
    {
        return Chatbot::query()->find($id);
    }

    /** @param array<string, mixed> $attributes */
    public function update(Chatbot $chatbot, array $attributes): Chatbot
    {
        $chatbot->fill($attributes);
        $chatbot->save();
        return $chatbot;
    }

    public function delete(Chatbot $chatbot): bool
    {
        return (bool) $chatbot->delete();
    }
}
