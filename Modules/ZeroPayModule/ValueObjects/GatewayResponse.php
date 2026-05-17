<?php

namespace Modules\ZeroPayModule\ValueObjects;

class GatewayResponse
{
    public function __construct(
        public readonly string $status,
        public readonly string $gateway,
        public readonly string $reference,
        public readonly array $data = [],
    ) {}

    public function toArray(): array
    {
        return array_merge([
            'status' => $this->status,
            'gateway' => $this->gateway,
            'reference' => $this->reference,
        ], $this->data);
    }
}
