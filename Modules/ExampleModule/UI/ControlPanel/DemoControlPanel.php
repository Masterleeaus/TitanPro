<?php

namespace Modules\ExampleModule\UI\ControlPanel;

/**
 * Demo Control Panel scaffold.
 *
 * UI/ is the component library. This class is the assembled one-page module panel.
 * Replace DemoAgent bindings before production.
 */
class DemoControlPanel
{
    public string $assistantName = 'Zero';
    public string $agent = 'demo.agent';

    public function layout(): array
    {
        return [
            'top' => [
                'widgets' => ['KpiOverviewWidget', 'ModuleHealthWidget', 'RecentRecordsWidget'],
                'operator' => 'ZeroOperatorCard',
                'shortcuts' => 'ShortcutCard',
                'settings' => 'ModuleSettingsForm',
            ],
            'bottom' => [
                'tables' => 'UnifiedTabbedTableCard',
            ],
        ];
    }
}
