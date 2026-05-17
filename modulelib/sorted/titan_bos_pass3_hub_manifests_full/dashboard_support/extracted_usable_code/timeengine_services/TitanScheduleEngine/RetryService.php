<?php

namespace App\Services\TitanScheduleEngine;

use App\Models\Tz\TzScheduledTask;
use Illuminate\Support\Carbon;

class RetryService
{
    public function nextRetryAt(TzScheduledTask $task): ?Carbon
    {
        $max = (int) ($task->max_retries ?: 3);
        if ((int) $task->retry_count >= $max) {
            return null;
        }

        return now()->addMinutes(max(5, ((int) $task->retry_count + 1) * 10));
    }
}
