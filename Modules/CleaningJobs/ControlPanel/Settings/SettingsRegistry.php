<?php

namespace Modules\CleaningJobs\ControlPanel\Settings;

class SettingsRegistry
{
    public static function getSettings(): array
    {
        return [
            [
                'key' => 'auto_convert_on_complete',
                'label' => 'Auto Convert Completed Jobs',
                'value' => (bool) config('cleaningjobs.automation.auto_convert_on_complete', false),
            ],
            [
                'key' => 'daily_job_reminders',
                'label' => 'Daily Job Reminders',
                'value' => true,
            ],
            [
                'key' => 'tenant_scope_enforced',
                'label' => 'Tenant Scope Enforced',
                'value' => true,
            ],
            [
                'key' => 'knowledge_checklists',
                'label' => 'Knowledge-Driven Checklists',
                'value' => true,
            ],
        ];
    }
}
