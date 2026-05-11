<?php

namespace Modules\TitanEchoAssist\Events;

class ConversationEscalated
{
    public function __construct(
        public readonly int $conversationId,
        public readonly mixed $chatbot = null,
        public readonly mixed $conversation = null,
    ) {}
}
