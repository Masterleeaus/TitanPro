<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuperAdminCommerceStats extends StatsOverviewWidget
{
    protected ?string $heading = 'SaaS Revenue Control';

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Plans', $this->safeCount('saas_packages'))
                ->description('Commercial plans available to the platform')
                ->color('primary'),
            Stat::make('Active Plans', $this->safeCountWhere('saas_packages', 'is_active', true))
                ->description('Visible or assignable plans')
                ->color('success'),
            Stat::make('Coupons', $this->safeCount('saas_coupons'))
                ->description('Discount and launch codes')
                ->color('warning'),
            Stat::make('Communications', $this->safeCount('platform_communicator_logs'))
                ->description('Platform-wide message log')
                ->color('info'),
        ];
    }

    private function safeCount(string $table): int|string
    {
        try {
            return Schema::hasTable($table) ? DB::table($table)->count() : 'Pending migration';
        } catch (\Throwable) {
            return '—';
        }
    }

    private function safeCountWhere(string $table, string $column, mixed $value): int|string
    {
        try {
            return Schema::hasTable($table) && Schema::hasColumn($table, $column)
                ? DB::table($table)->where($column, $value)->count()
                : 'Pending migration';
        } catch (\Throwable) {
            return '—';
        }
    }
}
