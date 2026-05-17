<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class TitanProBusinessStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Business Control Snapshot';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Customers', $this->countFirstAvailable(['customers', 'clients']))
                ->description('Customer records in this workspace')
                ->color('primary'),
            Stat::make('Open Jobs', $this->countMatching(['jobs', 'bookings'], ['status' => ['open', 'scheduled', 'in_progress', 'pending']]))
                ->description('Operational workload requiring attention')
                ->color('warning'),
            Stat::make('Quotes', $this->countFirstAvailable(['estimates', 'quotes']))
                ->description('Estimate and quoting pipeline')
                ->color('info'),
            Stat::make('Invoices', $this->countFirstAvailable(['invoices']))
                ->description('Billing records available')
                ->color('success'),
        ];
    }

    private function countFirstAvailable(array $tables): string
    {
        foreach ($tables as $table) {
            try {
                if (Schema::hasTable($table)) {
                    return (string) \DB::table($table)->count();
                }
            } catch (\Throwable) {
                return '—';
            }
        }

        return '0';
    }

    private function countMatching(array $tables, array $filters): string
    {
        foreach ($tables as $table) {
            try {
                if (! Schema::hasTable($table)) {
                    continue;
                }

                $query = \DB::table($table);

                foreach ($filters as $column => $values) {
                    if (Schema::hasColumn($table, $column)) {
                        $query->whereIn($column, $values);
                    }
                }

                return (string) $query->count();
            } catch (\Throwable) {
                return '—';
            }
        }

        return '0';
    }
}
