<?php

namespace App\Filament\TitanNexus\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait NexusPanelData
{
    protected function tableExists(string $table): bool
    {
        try { return Schema::hasTable($table); } catch (\Throwable $e) { return false; }
    }

    protected function rowCount(string $table): int
    {
        if (! $this->tableExists($table)) return 0;
        try { return DB::table($table)->count(); } catch (\Throwable $e) { return 0; }
    }

    protected function rowsFor(string $table, array $columns, int $limit = 12): array
    {
        if (! $this->tableExists($table)) return [];
        try {
            $available = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn($table, $column)));
            if ($available === []) return [];
            return DB::table($table)->select($available)->latest('id')->limit($limit)->get()->map(fn ($row): array => (array) $row)->all();
        } catch (\Throwable $e) { return []; }
    }

    protected function installedNexusTables(): array
    {
        return [];
    }

    protected function metricCards(array $metrics): array
    {
        return [];
    }
}
