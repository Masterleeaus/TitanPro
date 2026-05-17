<?php

namespace Modules\ExampleModule\UI\Adapters;

final class OptionalPluginRegistry
{
    public static function detected(): array
    {
        return [
            'shield' => class_exists('BezhanSalleh\FilamentShield\FilamentShieldPlugin'),
            'breezy' => class_exists('Jeffgreco13\FilamentBreezy\BreezyCore'),
            'spotlight' => class_exists('Pxlrbt\FilamentSpotlight\SpotlightPlugin'),
            'curator' => class_exists('Awcodes\Curator\CuratorPlugin'),
            'activity_log' => class_exists('Alizharb\FilamentActivitylog\FilamentActivitylogPlugin'),
            'advanced_widgets' => class_exists('Eightynine\AdvancedWidgets\AdvancedWidgetsPlugin'),
            'dash_arrange' => class_exists('Shreejan\DashArrange\DashArrangePlugin'),
            'apex_charts' => class_exists('Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin'),
        ];
    }
}
