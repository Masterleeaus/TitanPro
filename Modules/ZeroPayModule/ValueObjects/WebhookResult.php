<?php

namespace Modules\ZeroPayModule\ValueObjects;

class WebhookResult
{
    public function __construct(
        public readonly bool $processed,
        public readonly string $gateway,
        public readonly array $payload = [],
    ) {}

    public function toArray(): array
    {
        return [
            'processed' => $this->processed,
            'gateway' => $this->gateway,
            'payload' => $this->payload,
        ];
    }
}
