<?php

namespace Modules\Payroll\Services\Forecasting;

class PayrollForecastService
{
    public function projectMonthlyCost(float $currentMonthlyNet, float $growthRate = 0.0, int $months = 12): array
    {
        $forecast = [];
        $running = $currentMonthlyNet;
        for ($month = 1; $month <= $months; $month++) {
            $running *= (1 + $growthRate);
            $forecast[] = ['month' => $month, 'projected_net_payroll' => round($running, 2)];
        }
        return $forecast;
    }
}
