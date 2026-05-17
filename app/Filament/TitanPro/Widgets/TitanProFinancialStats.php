<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitanProFinancialStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Revenue Pulse';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Estimates', $this->safeCount('estimates'))->description('Quote pipeline records')->color('info'),
            Stat::make('Invoices', $this->safeCount('invoices'))->description('Billing records')->color('info'),
            Stat::make('Payments', $this->safeCount('payments'))->description('Payment records')->color('info'),
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
