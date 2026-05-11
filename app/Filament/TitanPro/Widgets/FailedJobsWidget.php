<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FailedJobsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Failed Jobs';

    protected static ?int $sort = 4;

    public static function metrics(): array
    {
        return Cache::remember('titanpro.widgets.failed-jobs', 60, function (): array {
            $lastFailureAt = DB::table('failed_jobs')->max('failed_at');

            return [
                'count' => (int) DB::table('failed_jobs')->count(),
                'last_failure_at' => $lastFailureAt ? Carbon::parse($lastFailureAt) : null,
            ];
        });
    }

    protected function getStats(): array
    {
        $metrics = self::metrics();
        $lastFailureLabel = $metrics['last_failure_at'] instanceof Carbon
            ? $metrics['last_failure_at']->toDateTimeString()
            : 'No failures recorded';

        return [
            Stat::make('Failed jobs in queue', (string) $metrics['count']),
            Stat::make('Last failure', $lastFailureLabel),
        ];
    }
}
