<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;

class SuperAdminHealthStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Health & Repair Snapshot';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $issues = $this->healthIssues();

        return [
            Stat::make('Repair Status', $issues === 0 ? 'Ready' : $issues . ' checks')
                ->description($issues === 0 ? 'No blocking configuration issues detected' : 'Review Module Health & Repair')
                ->color($issues === 0 ? 'success' : 'warning'),
            Stat::make('Writable Cache', $this->isWritable(base_path('bootstrap/cache')) ? 'OK' : 'Check')
                ->description('bootstrap/cache write access')
                ->color($this->isWritable(base_path('bootstrap/cache')) ? 'success' : 'danger'),
            Stat::make('Writable Storage', $this->isWritable(storage_path()) ? 'OK' : 'Check')
                ->description('storage directory write access')
                ->color($this->isWritable(storage_path()) ? 'success' : 'danger'),
            Stat::make('Environment', app()->environment())
                ->description('Current Laravel environment')
                ->color(app()->environment('production') ? 'success' : 'warning'),
        ];
    }

    private function healthIssues(): int
    {
        $checks = [
            File::isDirectory(base_path('Modules')),
            File::isDirectory(base_path('bootstrap/cache')) && $this->isWritable(base_path('bootstrap/cache')),
            File::isDirectory(storage_path()) && $this->isWritable(storage_path()),
            File::exists(base_path('composer.json')),
        ];

        return collect($checks)->filter(fn (bool $ok): bool => ! $ok)->count();
    }

    private function isWritable(string $path): bool
    {
        return is_dir($path) && is_writable($path);
    }
}
