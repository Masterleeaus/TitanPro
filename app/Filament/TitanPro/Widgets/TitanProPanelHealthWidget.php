<?php

namespace App\Filament\TitanPro\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;

class TitanProPanelHealthWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Titan Pro Readiness';

    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $checks = $this->checks();
        $ready = count(array_filter($checks));
        $total = count($checks);

        return [
            Stat::make('Panel Readiness', $ready . '/' . $total)
                ->description('Configured namespaces and modules')
                ->color($ready === $total ? 'success' : 'warning'),
            Stat::make('CRM Core', $checks['crm'] ? 'Available' : 'Missing')
                ->description('Lead, pipeline, deal, and account growth features')
                ->color($checks['crm'] ? 'success' : 'gray'),
            Stat::make('Field Ops', $checks['field'] ? 'Available' : 'Missing')
                ->description('Mobile and field execution bridge')
                ->color($checks['field'] ? 'success' : 'gray'),
            Stat::make('Pro Namespace', $checks['namespace'] ? 'Ready' : 'Missing')
                ->description('Panel-specific pages and widgets')
                ->color($checks['namespace'] ? 'success' : 'warning'),
        ];
    }

    private function checks(): array
    {
        return [
            'provider' => File::exists(app_path('Providers/Filament/TitanProPanelProvider.php')),
            'namespace' => File::isDirectory(app_path('Filament/TitanPro')),
            'crm' => File::isDirectory(base_path('Modules/CRMCore')),
            'field' => File::isDirectory(base_path('Modules/TitanGoField')),
        ];
    }
}
