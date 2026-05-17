<?php

namespace Modules\Security\Support\DTOs;

class SecurityWorkflowTransitionResult
{
    public function __construct(
        public readonly bool $ok,
        public readonly string $message,
        public readonly ?string $state = null,
        public readonly array $meta = [],
    ) {
    }

    public function toArray(): array
    {
        return [
            'ok' => $this->ok,
            'message' => $this->message,
            'state' => $this->state,
            'meta' => $this->meta,
        ];
    }
}
