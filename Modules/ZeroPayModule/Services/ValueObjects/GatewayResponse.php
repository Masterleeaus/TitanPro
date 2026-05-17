<?php

namespace Modules\ZeroPayModule\Services\ValueObjects;

class GatewayResponse
{
    public function __construct(
        public bool $success,
        public string $reference,
        public string $status,
        public array $rawResponse = [],
        public ?string $redirectUrl = null,
        public ?string $failureReason = null,
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'reference' => $this->reference,
            'status' => $this->status,
            'raw_response' => $this->rawResponse,
            'redirect_url' => $this->redirectUrl,
            'failure_reason' => $this->failureReason,
        ];
    }
}
