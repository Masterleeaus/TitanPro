<?php

namespace App\Support;

class ThemeComponentRegistry
{
    public static function components(): array
    {
        return [
            'button' => ['label' => 'Button', 'tokens' => ['radius', 'color', 'motion']],
            'card' => ['label' => 'Card', 'tokens' => ['surface', 'shadow', 'radius']],
            'modal' => ['label' => 'Modal', 'tokens' => ['surface', 'overlay', 'radius', 'motion']],
            'sidebar' => ['label' => 'Sidebar', 'tokens' => ['background', 'text', 'active']],
            'topbar' => ['label' => 'Topbar', 'tokens' => ['surface', 'border', 'text']],
            'table' => ['label' => 'Table', 'tokens' => ['row', 'border', 'density']],
            'form-input' => ['label' => 'Form Input', 'tokens' => ['border', 'focus', 'radius']],
            'badge' => ['label' => 'Badge', 'tokens' => ['color', 'radius']],
            'dropdown' => ['label' => 'Dropdown', 'tokens' => ['surface', 'shadow', 'motion']],
            'tabs' => ['label' => 'Tabs', 'tokens' => ['active', 'border', 'text']],
            'alert' => ['label' => 'Alert', 'tokens' => ['semantic-color', 'radius']],
            'avatar' => ['label' => 'Avatar', 'tokens' => ['radius', 'ring']],
            'breadcrumb' => ['label' => 'Breadcrumb', 'tokens' => ['text', 'separator']],
            'pagination' => ['label' => 'Pagination', 'tokens' => ['active', 'radius']],
            'stat-card' => ['label' => 'Stat Card', 'tokens' => ['surface', 'accent']],
            'chart-card' => ['label' => 'Chart Card', 'tokens' => ['surface', 'grid']],
            'empty-state' => ['label' => 'Empty State', 'tokens' => ['text', 'icon']],
            'command-menu' => ['label' => 'Command Menu', 'tokens' => ['surface', 'focus']],
            'nav-item' => ['label' => 'Navigation Item', 'tokens' => ['active', 'hover']],
            'toast' => ['label' => 'Toast', 'tokens' => ['surface', 'semantic-color']],
            'wizard' => ['label' => 'Wizard', 'tokens' => ['step', 'active']],
            'kanban-card' => ['label' => 'Kanban Card', 'tokens' => ['surface', 'shadow']],
        ];
    }

    public static function diagnostics(): array
    {
        return [
            'count' => count(self::components()),
            'components' => self::components(),
        ];
    }
}
