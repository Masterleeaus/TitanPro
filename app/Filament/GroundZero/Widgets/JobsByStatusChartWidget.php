<?php

namespace App\Filament\GroundZero\Widgets;

use App\Models\Job;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

/**
 * Jobs by status donut chart for the GroundZero dispatch dashboard.
 */
class JobsByStatusChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'jobs-by-status';

    protected static ?string $heading = 'Jobs by Status';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    protected function getOptions(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            $counts = [];
        } else {
            $counts = Job::query()
                ->where('organization_id', $organizationId)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }

        $statuses = Job::statuses();
        $labels = [];
        $data = [];

        foreach ($statuses as $key => $label) {
            if (isset($counts[$key]) && $counts[$key] > 0) {
                $labels[] = $label;
                $data[] = (int) $counts[$key];
            }
        }

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => $data,
            'labels' => $labels,
            'legend' => [
                'position' => 'bottom',
                'fontFamily' => 'inherit',
            ],
            'colors' => ['#06b6d4', '#3b82f6', '#f59e0b', '#10b981', '#ef4444', '#6b7280'],
            'tooltip' => ['style' => ['fontFamily' => 'inherit']],
        ];
    }
}
