<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitanProWorkflowStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Workflow Pulse';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Job Messages', $this->safeCount('job_messages'))->description('Customer and field communications')->color('info'),
            Stat::make('Checklist Items', $this->safeCount('job_checklist_items'))->description('Execution checklist records')->color('info'),
            Stat::make('Driver Locations', $this->safeCount('driver_locations'))->description('Live field location records')->color('info'),
        ];
    }

    private function safeCount(string $table): int|string
    {
        try {
            if (! Schema::hasTable($table)) {
                return '—';
            }

            return DB::table($table)->count();
        } catch (\Throwable) {
            return '—';
        }
    }
}
