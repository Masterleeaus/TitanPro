<?php

namespace Modules\Payroll\Support\DTOs;

final class PayslipDocument
{
    public function __construct(
        public readonly int $userId,
        public readonly string $periodFrom,
        public readonly string $periodTo,
        public readonly string $html,
        public readonly array $payload = [],
        public readonly ?string $storagePath = null,
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'period_from' => $this->periodFrom,
            'period_to' => $this->periodTo,
            'html' => $this->html,
            'payload' => $this->payload,
            'storage_path' => $this->storagePath,
        ];
    }
}
