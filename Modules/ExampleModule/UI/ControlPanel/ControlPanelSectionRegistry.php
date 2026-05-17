<?php

namespace Modules\ExampleModule\UI\ControlPanel;


final class ControlPanelSectionRegistry
{
    public static function defaults(): array
    {
        return [
            'metrics' => 'Top KPI cards',
            'quick_actions' => 'Primary operator actions',
            'module_health' => 'Install, tenancy, permissions, queue state',
            'workspace' => 'Current working context',
            'chart' => 'Apex/advanced widget chart area',
            'timeline' => 'Audit and activity stream',
            'notes' => 'Module-local notes/checklists',
            'media' => 'Curator/Uppy attachments',
            'agent' => 'Module AI assistant surface',
            'records' => 'Bottom tabbed tables',
            'activity' => 'Recent events and jobs',
            'settings' => 'Module settings and permissions',
        ];
    }

}
