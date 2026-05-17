<?php

namespace Modules\ZeroPayModule\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayRevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = '30-Day Revenue by Gateway';

    protected static ?int $sort = 2;

    protected static string $type = 'line';

    protected function getData(): array
    {
        $gatewayLabels = [
            'payid' => 'PayID',
            'bank_transfer' => 'Bank Transfer',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'cash' => 'Cash',
            'cryptomus' => 'Cryptomus',
        ];

        $colors = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#6366f1'];

        $labels = [];
        for ($i = 29; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M d');
        }

        $datasets = [];
        $colorIndex = 0;

        foreach (array_keys($gatewayLabels) as $gateway) {
            $dailyRevenue = ZeroPayTransaction::query()
                ->where('gateway', $gateway)
                ->where('created_at', '>=', now()->subDays(30)->startOfDay())
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray();

            $values = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $values[] = (float) ($dailyRevenue[$date] ?? 0);
            }

            if (array_sum($values) > 0) {
                $color = $colors[$colorIndex % count($colors)];
                $datasets[] = [
                    'label' => $gatewayLabels[$gateway],
                    'data' => $values,
                    'borderColor' => $color,
                    'backgroundColor' => $color.'33',
                    'fill' => false,
                    'tension' => 0.3,
                ];
                $colorIndex++;
            }
        }

        if (empty($datasets)) {
            $datasets = [[
                'label' => 'No data',
                'data' => array_fill(0, 30, 0),
            ]];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
