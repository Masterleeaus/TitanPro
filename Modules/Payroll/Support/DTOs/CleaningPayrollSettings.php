<?php

namespace Modules\Payroll\Support\DTOs;

class CleaningPayrollSettings
{
    public function __construct(
        public readonly array $loadings = [],
        public readonly array $allowances = [],
        public readonly array $variance = [],
        public readonly array $contractors = [],
        public readonly array $selfService = [],
    ) {}

    public static function fromArray(array $settings): self
    {
        return new self(
            loadings: $settings['loadings'] ?? [],
            allowances: $settings['allowances'] ?? [],
            variance: $settings['variance'] ?? [],
            contractors: $settings['contractors'] ?? [],
            selfService: $settings['self_service'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'loadings' => $this->loadings,
            'allowances' => $this->allowances,
            'variance' => $this->variance,
            'contractors' => $this->contractors,
            'self_service' => $this->selfService,
        ];
    }
}
