<?php

namespace Modules\CleaningJobs\ControlPanel\Tables;

/**
 * TabsRegistry lists the table tabs available on the lower half of the TitanWork control panel.
 *
 * Each entry represents a tab name and the provider class responsible for fetching
 * and preparing the data for that tab. These providers may reuse existing
 * Filament tables or integrate with the host system.
 */
class TabsRegistry
{
    public static function getTabs(): array
    {
        return [
            [
                'key' => 'jobs',
                'label' => 'Jobs',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\JobsTableProvider::class,
            ],
            [
                'key' => 'requests',
                'label' => 'Requests',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\RequestsTableProvider::class,
            ],
            [
                'key' => 'checklists',
                'label' => 'Checklists',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\ChecklistsTableProvider::class,
            ],
            [
                'key' => 'supplies_used',
                'label' => 'Supplies Used',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\SuppliesUsedTableProvider::class,
            ],
            [
                'key' => 'supplies',
                'label' => 'Supplies',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\SuppliesTableProvider::class,
            ],
            [
                'key' => 'appointments',
                'label' => 'Appointments',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\AppointmentsTableProvider::class,
            ],
            [
                'key' => 'inspections',
                'label' => 'Inspections',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\InspectionsTableProvider::class,
            ],
            [
                'key' => 'photos',
                'label' => 'Photos',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\PhotosTableProvider::class,
            ],
            [
                'key' => 'issues',
                'label' => 'Issues',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\IssuesTableProvider::class,
            ],
            [
                'key' => 'recurring_plans',
                'label' => 'Recurring Plans',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\RecurringPlansTableProvider::class,
            ],
            [
                'key' => 'automations',
                'label' => 'Automations',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\AutomationsTableProvider::class,
            ],
            [
                'key' => 'agent_logs',
                'label' => 'Agent Logs',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\AgentLogsTableProvider::class,
            ],
            [
                'key' => 'knowledge',
                'label' => 'Knowledge',
                'provider' => \Modules\CleaningJobs\ControlPanel\Tables\Providers\KnowledgeTableProvider::class,
            ],
        ];
    }
}