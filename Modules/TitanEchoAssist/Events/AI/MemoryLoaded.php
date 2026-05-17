<?php

namespace Modules\TitanEchoAssist\Events\AI;

class MemoryLoaded
{
    public function __construct(
        public readonly string $sessionId,
        public readonly int $messageCount,
    ) {}
}
