<?php

namespace Modules\TitanEchoAssist\Events\AI;

class BeforeSend
{
    public function __construct(
        public readonly string $message,
        public readonly string $sessionId,
        public readonly string $agentClass,
    ) {}
}
