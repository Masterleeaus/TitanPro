<?php

namespace Modules\TitanEchoAssist\Events;

use Modules\TitanEchoAssist\DTOs\MessagePayload;

class ConversationStarted
{
    public function __construct(
        public readonly MessagePayload $payload,
        public readonly mixed $chatbot = null,
    ) {}
}
