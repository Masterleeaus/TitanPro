<?php

namespace Modules\TitanChatbot\Queries;

use Modules\TitanChatbot\Models\Chatbot;

class ChatbotQuery
{
    public function builder(): mixed
    {
        return Chatbot::query();
    }

    public function active(): mixed
    {
        $query = $this->builder();
        return method_exists($query, 'where') ? $query->where('status', 'active') : $query;
    }
}
