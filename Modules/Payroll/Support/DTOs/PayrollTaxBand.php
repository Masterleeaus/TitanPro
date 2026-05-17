<?php

namespace Modules\Payroll\Support\DTOs;

final class PayrollTaxBand
{
    public function __construct(
        public readonly float $from,
        public readonly ?float $to,
        public readonly float $rate,
        public readonly float $base = 0.0,
        public readonly string $label = '',
    ) {}

    public function applies(float $income): bool
    {
        return $income >= $this->from && ($this->to === null || $income <= $this->to);
    }
}
