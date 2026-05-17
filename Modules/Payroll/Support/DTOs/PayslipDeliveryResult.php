<?php

namespace Modules\Payroll\Support\DTOs;

final class PayslipDeliveryResult
{
    public function __construct(
        public readonly int $userId,
        public readonly string $status,
        public readonly array $channels = [],
        public readonly ?string $recipient = null,
        public readonly ?string $message = null,
        public readonly array $metadata = [],
    ) {}

    public function delivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'status' => $this->status,
            'channels' => $this->channels,
            'recipient' => $this->recipient,
            'message' => $this->message,
            'metadata' => $this->metadata,
        ];
    }
}
