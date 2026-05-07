<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

/**
 * Revenue over time chart widget for the Admin (TitanPro) dashboard.
 *
 * Renders monthly revenue totals for the authenticated user's organisation
 * using an ApexCharts line chart.
 */
class RevenueChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'revenue-over-time';

    protected static ?string $heading = 'Revenue Over Time';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $organizationId = auth()->user()?->organization_id;

        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        $labels = $months->map(fn ($m) => $m->format('M Y'))->toArray();

        $data = $months->map(function ($month) use ($organizationId) {
            if ($organizationId === null) {
                return 0;
            }

            return Invoice::query()
                ->where('organization_id', $organizationId)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->whereIn('status', ['paid', 'partial'])
                ->sum('total');
        })->toArray();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Revenue',
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $labels,
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => ['fontFamily' => 'inherit'],
                    'formatter' => 'function (val) { return "$" + val.toFixed(0); }',
                ],
            ],
            'colors' => ['#3b82f6'],
            'stroke' => ['curve' => 'smooth', 'width' => 2],
            'tooltip' => ['y' => ['formatter' => 'function (val) { return "$" + val.toFixed(2); }']],
        ];
    }
}
