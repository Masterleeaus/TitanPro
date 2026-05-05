<?php

namespace Modules\TitanChatbot\ViewModels;

use Modules\TitanChatbot\Models\Chatbot;

class ChatbotViewModel
{
    public function __construct(private readonly Chatbot $chatbot) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->chatbot->getAttribute('id'),
            'name' => $this->chatbot->getAttribute('name'),
            'status' => $this->chatbot->getAttribute('status'),
            'updated_at' => $this->chatbot->getAttribute('updated_at'),
        ];
    }
}
