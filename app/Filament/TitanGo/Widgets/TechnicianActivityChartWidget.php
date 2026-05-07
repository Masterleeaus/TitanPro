<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\Job;
use App\Models\User;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

/**
 * Technician activity bar chart for the TitanGo field-ops dashboard.
 *
 * Displays the number of active jobs per technician so dispatchers can
 * quickly see crew workload at a glance.
 */
class TechnicianActivityChartWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'technician-activity';

    protected static ?string $heading = 'Technician Activity';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            $names = [];
            $counts = [];
        } else {
            $technicians = User::query()
                ->where('organization_id', $organizationId)
                ->whereHas('roles', fn ($q) => $q->where('name', 'technician'))
                ->withCount(['jobs as active_jobs_count' => fn ($q) => $q
                    ->whereIn('status', [
                        Job::STATUS_SCHEDULED,
                        Job::STATUS_ASSIGNED,
                        Job::STATUS_EN_ROUTE,
                        Job::STATUS_IN_PROGRESS,
                    ])])
                ->orderByDesc('active_jobs_count')
                ->limit(10)
                ->get(['id', 'name']);

            $names = $technicians->pluck('name')->toArray();
            $counts = $technicians->pluck('active_jobs_count')->map(fn ($v) => (int) $v)->toArray();
        }

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Active Jobs',
                    'data' => $counts,
                ],
            ],
            'xaxis' => [
                'categories' => $names,
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'labels' => ['style' => ['fontFamily' => 'inherit']],
                'min' => 0,
                'tickAmount' => 4,
            ],
            'colors' => ['#f97316'],
            'plotOptions' => [
                'bar' => ['borderRadius' => 4, 'horizontal' => false],
            ],
            'dataLabels' => ['enabled' => false],
        ];
    }
}
