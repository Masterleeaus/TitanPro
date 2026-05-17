<?php

namespace Modules\Payroll\Services\Domain;

use Modules\Payroll\Contracts\Services\PayrollTaxServiceContract;
use Modules\Payroll\Support\DTOs\PayrollTaxBand;

class PayrollTaxService implements PayrollTaxServiceContract
{
    public function estimate(float $taxableIncome, string $country = 'AU', array $context = []): array
    {
        $bands = array_map(fn ($band) => new PayrollTaxBand((float) $band['from'], $band['to'] === null ? null : (float) $band['to'], (float) $band['rate'], (float) ($band['base'] ?? 0), (string) ($band['label'] ?? '')), config("payroll.tax.bands.$country", []));
        $match = null;
        foreach ($bands as $band) {
            if ($band->applies($taxableIncome)) {
                $match = $band;
                break;
            }
        }
        if (! $match) {
            return ['country' => $country, 'taxable_income' => $taxableIncome, 'tax' => 0.0, 'band' => null, 'warnings' => ['No tax band configured.']];
        }
        $tax = $match->base + max(0, $taxableIncome - $match->from) * $match->rate;
        return ['country' => $country, 'taxable_income' => round($taxableIncome, 2), 'tax' => round($tax, 2), 'effective_rate' => $taxableIncome > 0 ? round($tax / $taxableIncome, 4) : 0, 'band' => $match->label];
    }
}
