<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollTaxServiceContract
{
    public function estimate(float $taxableIncome, string $country = 'AU', array $context = []): array;
}
