<?php

namespace App\Models\Tz;

class TzScheduledTaskRun extends AbstractTzTenantModel
{
    protected $table = 'tz_scheduled_task_runs';

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'result_json' => 'array',
        ];
    }
}
