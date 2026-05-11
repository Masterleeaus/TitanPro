<?php

namespace Modules\TitanEchoAssist\Events;

class VoiceSessionDurationRecorded
{
    public function __construct(
        public readonly string $sessionId,
        public readonly float $durationSeconds,
        public readonly int $messageCount,
    ) {}
}
