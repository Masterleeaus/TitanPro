<?php

namespace Modules\BookingModule\Verticals\Support;

class VerticalContext
{
    public function __construct(public readonly string $vertical = 'services', public readonly ?int $companyId = null, public readonly array $config = []) {}

    public function toArray(): array
    {
        return ['vertical' => $this->vertical, 'company_id' => $this->companyId, 'config' => $this->config];
    }
}
