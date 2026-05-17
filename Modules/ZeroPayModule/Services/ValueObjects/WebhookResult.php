<?php

namespace Modules\ZeroPayModule\Services\ValueObjects;

class WebhookResult
{
    public function __construct(
        public bool $processed,
        public string $status,
        public array $rawResponse = [],
        public ?string $failureReason = null,
    ) {}

    public function toArray(): array
    {
        return [
            'processed' => $this->processed,
            'status' => $this->status,
            'raw_response' => $this->rawResponse,
            'failure_reason' => $this->failureReason,
        ];
    }
}
