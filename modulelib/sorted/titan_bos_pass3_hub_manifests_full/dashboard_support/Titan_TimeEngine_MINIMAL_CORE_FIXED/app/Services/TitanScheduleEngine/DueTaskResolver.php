<?php

namespace App\Services\TitanScheduleEngine;

use App\Models\Tz\TzScheduledTask;
use Illuminate\Support\Facades\Schema;

class DueTaskResolver
{
    public function due(int $limit = 100): iterable
    {
        if (! Schema::hasTable('tz_scheduled_tasks')) {
            return collect();
        }

        return TzScheduledTask::query()
            ->whereIn('status', ['pending', 'queued', 'failed'])
            ->where(function ($query): void {
                $query->whereNull('next_run_at')->orWhere('next_run_at', '<=', now());
            })
            ->where('scheduled_for', '<=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('scheduled_for')
            ->limit($limit)
            ->get();
    }
}
