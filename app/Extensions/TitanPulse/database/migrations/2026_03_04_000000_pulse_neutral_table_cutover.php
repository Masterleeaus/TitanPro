<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Titan Pulse neutral-table cutover (safe).
 * If legacy tz_work_* tables exist, rename them to neutral tz_* tables.
 * Otherwise, the normal create_* migrations will create the neutral tables.
 */
return new class extends Migration {
    public function up(): void
    {
        $map = [
            'tz_work_automation_rules' => 'tz_automation_rules',
            'tz_work_ai_suggestions'   => 'tz_ai_suggestions',
            'tz_work_pending_actions'  => 'tz_pending_actions',
            'tz_work_analyses'         => 'tz_analyses',
            'tz_work_agent_runs'       => 'tz_automation_runs',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }
    }

    public function down(): void
    {
        $map = [
            'tz_automation_rules' => 'tz_work_automation_rules',
            'tz_ai_suggestions'   => 'tz_work_ai_suggestions',
            'tz_pending_actions'  => 'tz_work_pending_actions',
            'tz_analyses'         => 'tz_work_analyses',
            'tz_automation_runs'  => 'tz_work_agent_runs',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }
    }
};
