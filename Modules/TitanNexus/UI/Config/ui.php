<?php

return [
    'control_panel' => [
        'enabled' => true,
        'layout' => 'one-page',
        'sections' => ['metrics', 'actions', 'workspace', 'timeline', 'media', 'agent', 'tables', 'settings'],
    ],
    'plugins' => [
        'shield', 'breezy', 'spotlight', 'curator', 'activity_log', 'advanced_widgets', 'dash_arrange', 'apex_charts', 'uppy',
    ],
];
