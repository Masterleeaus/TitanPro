<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class TitanOverviewWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Titan Overview';

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Users', $this->safeCount('users'))
                ->description('Registered platform accounts')
                ->color('info'),
            Stat::make('Modules', $this->safeCount('modules'))
                ->description('Installed module records')
                ->color('success'),
            Stat::make('Settings', $this->safeCount('platform_settings'))
                ->description('Platform settings records')
                ->color('gray'),
        ];
    }

    private function safeCount(string $table): int|string
    {
        try {
            if (! Schema::hasTable($table)) {
                return '—';
            }

            return \DB::table($table)->count();
        } catch (\Throwable) {
            return '—';
        }
    }
}
