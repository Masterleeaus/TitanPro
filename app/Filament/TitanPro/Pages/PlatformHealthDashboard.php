<?php

namespace App\Filament\TitanPro\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlatformHealthDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Platform Health';

    protected static ?int $navigationSort = 20;

    protected ?string $heading = 'Platform Health';

    protected string $view = 'filament.titanpro.pages.platform-health-dashboard';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }

    public function getHealthData(): array
    {
        $pendingJobs = Schema::hasTable('jobs') ? DB::table('jobs')->count() : 0;
        $failedJobs = Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0;
        $recentFailedJobs = Schema::hasTable('failed_jobs')
            ? DB::table('failed_jobs')->where('failed_at', '>=', now()->subHour())->count()
            : 0;

        $totalTracked = $pendingJobs + $failedJobs;
        $errorRate = $totalTracked === 0 ? null : round(($failedJobs / $totalTracked) * 100, 2);

        return [
            'queue_depth' => $pendingJobs,
            'failed_jobs' => $failedJobs,
            'failed_jobs_last_hour' => $recentFailedJobs,
            'error_rate_percent' => $errorRate,
        ];
    }
}
