<?php

namespace Modules\ZeroPayModule\Data;

class ZeroPaySessionData
{
    public function __construct(
        public int $companyId,
        public int $userId,
        public string $name,
        public string $sessionToken,
        public string $gateway = 'bank_transfer',
        public ?float $amount = null,
        public string $currency = 'AUD',
        public string $status = 'pending',
        public array $meta = [],
    ) {}

    public static function fromArray(array $p): self
    {
        return new self(
            companyId: (int) $p['company_id'],
            userId: (int) $p['user_id'],
            name: (string) ($p['name'] ?? ''),
            sessionToken: (string) ($p['session_token'] ?? ''),
            gateway: (string) ($p['gateway'] ?? 'bank_transfer'),
            amount: isset($p['amount']) ? (float) $p['amount'] : null,
            currency: (string) ($p['currency'] ?? 'AUD'),
            status: (string) ($p['status'] ?? 'pending'),
            meta: (array) ($p['meta'] ?? []),
        );
    }

    public function toArray(): array
    {
        return [
            'company_id' => $this->companyId,
            'user_id' => $this->userId,
            'name' => $this->name,
            'session_token' => $this->sessionToken,
            'gateway' => $this->gateway,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'meta' => $this->meta,
        ];
    }
}
