<?php

namespace Modules\ZeroPayModule\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayGatewayBreakdownWidget extends ChartWidget
{
    protected static ?string $heading = 'Transactions by Gateway';

    protected static ?int $sort = 3;

    protected static string $type = 'doughnut';

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

        $breakdown = ZeroPayTransaction::query()
            ->selectRaw('gateway, COUNT(*) as count')
            ->groupBy('gateway')
            ->pluck('count', 'gateway')
            ->toArray();

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $colorKeys = array_keys($gatewayLabels);

        foreach ($breakdown as $gateway => $count) {
            $labels[] = $gatewayLabels[$gateway] ?? ucfirst(str_replace('_', ' ', $gateway));
            $data[] = $count;
            $colorIndex = array_search($gateway, $colorKeys);
            $backgroundColors[] = $colorIndex !== false ? $colors[$colorIndex] : '#94a3b8';
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
