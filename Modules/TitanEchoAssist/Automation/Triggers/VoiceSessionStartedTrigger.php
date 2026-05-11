<?php

namespace Modules\TitanEchoAssist\Automation\Triggers;

use Modules\TitanEchoAssist\Events\VoiceSessionStarted;

class VoiceSessionStartedTrigger
{
    public function name(): string
    {
        return 'voice_session_started';
    }

    public function description(): string
    {
        return 'Fires when a voice session is initiated.';
    }

    public function eventClass(): string
    {
        return VoiceSessionStarted::class;
    }
}
