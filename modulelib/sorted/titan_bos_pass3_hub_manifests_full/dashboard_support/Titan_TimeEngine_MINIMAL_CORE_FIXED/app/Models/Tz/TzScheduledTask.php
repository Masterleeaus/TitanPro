<?php

namespace App\Models\Tz;

class TzScheduledTask extends AbstractTzTenantModel
{
    protected $table = 'tz_scheduled_tasks';

    protected function casts(): array
    {
        return [
            'payload_json' => 'array',
            'result_json' => 'array',
            'scheduled_for' => 'datetime',
            'next_run_at' => 'datetime',
            'last_run_at' => 'datetime',
            'executed_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }
}
