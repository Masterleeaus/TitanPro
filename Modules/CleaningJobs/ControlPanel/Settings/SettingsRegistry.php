<?php

namespace Modules\CleaningJobs\ControlPanel\Settings;

/**
 * SettingsRegistry defines configurable options surfaced in the TitanWork control panel.
 *
 * These settings allow administrators or supervisors to adjust module behaviour
 * without modifying code, such as enabling automatic checklist generation or
 * toggling supply tracking.
 */
class SettingsRegistry
{
    /**
     * Return an array of configurable settings.
     *
     * Each setting should include a unique key, a label for the UI and the
     * current value (which would normally be persisted via the database or config file).
     */
    public static function getSettings(): array
    {
        return [
            [
                'key' => 'checklist_templates',
                'label' => 'Checklist Templates',
                'value' => false,
            ],
            [
                'key' => 'inspection_rules',
                'label' => 'Inspection Rules',
                'value' => false,
            ],
            [
                'key' => 'supply_tracking',
                'label' => 'Supply Tracking',
                'value' => false,
            ],
            [
                'key' => 'automation_policies',
                'label' => 'Automation Policies',
                'value' => false,
            ],
        ];
    }
}