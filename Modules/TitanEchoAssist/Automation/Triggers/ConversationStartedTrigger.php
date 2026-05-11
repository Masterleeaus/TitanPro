<?php

namespace Modules\TitanEchoAssist\Automation\Triggers;

use Modules\TitanEchoAssist\Events\ConversationStarted;

class ConversationStartedTrigger
{
    public function name(): string
    {
        return 'conversation_started';
    }

    public function description(): string
    {
        return 'Fires when a new conversation is initiated by a user.';
    }

    public function eventClass(): string
    {
        return ConversationStarted::class;
    }
}
