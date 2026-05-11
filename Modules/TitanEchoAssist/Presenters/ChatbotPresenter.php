<?php

namespace Modules\TitanEchoAssist\Presenters;

use Modules\TitanEchoAssist\Models\Chatbot;

class ChatbotPresenter
{
    public function title(Chatbot $chatbot): string
    {
        return (string) ($chatbot->getAttribute('name') ?: 'Untitled chatbot');
    }

    /** @return array<string, mixed> */
    public function card(Chatbot $chatbot): array
    {
        return [
            'title' => $this->title($chatbot),
            'status' => $chatbot->getAttribute('status'),
            'channel' => $chatbot->getAttribute('channel_type'),
        ];
    }
}
