<?php

namespace Modules\Payroll\Support\DTOs;

final class PayslipDeliveryEnvelope
{
    public function __construct(
        public readonly PayslipDocument $document,
        public readonly array $employee = [],
        public readonly array $options = [],
        public readonly ?string $deliveryId = null,
        public readonly ?string $accessUrl = null,
    ) {}

    public function toArray(): array
    {
        return [
            'delivery_id' => $this->deliveryId,
            'access_url' => $this->accessUrl,
            'employee' => $this->employee,
            'document' => $this->document->toArray(),
            'options' => $this->options,
        ];
    }
}
