<?php

namespace App\Models\Tz;

class TzScheduledTaskLog extends AbstractTzTenantModel
{
    protected $table = 'tz_scheduled_task_logs';

    protected function casts(): array
    {
        return [
            'context_json' => 'array',
        ];
    }
}
