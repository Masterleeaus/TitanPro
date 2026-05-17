<?php

namespace Modules\ZeroPayModule\UI\ControlPanel;

final class ControlPanelLayout
{
    public static function sections(): array
    {
        return [
            'header' => ['metrics', 'quick_actions', 'module_health'],
            'main' => ['workspace', 'chart', 'timeline'],
            'side' => ['notes', 'media', 'agent'],
            'bottom' => ['records', 'activity', 'settings'],
        ];
    }
}
