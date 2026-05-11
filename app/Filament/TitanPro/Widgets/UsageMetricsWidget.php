<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UsageMetricsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Usage Metrics';

    protected static ?int $sort = 3;

    public static function metrics(): array
    {
        return Cache::remember('titanpro.widgets.usage-metrics', 60, fn (): array => [
            'jobs' => (int) DB::table('field_jobs')
                ->whereNull('deleted_at')
                ->count(),
            'invoices' => (int) DB::table('invoices')
                ->whereNull('deleted_at')
                ->count(),
            'payments' => (int) DB::table('payments')->count(),
        ]);
    }

    protected function getStats(): array
    {
        $metrics = self::metrics();

        return [
            Stat::make('Total Jobs', (string) $metrics['jobs']),
            Stat::make('Total Invoices', (string) $metrics['invoices']),
            Stat::make('Total Payments', (string) $metrics['payments']),
        ];
    }
}
