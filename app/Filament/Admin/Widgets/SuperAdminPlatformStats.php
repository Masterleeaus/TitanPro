<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SuperAdminPlatformStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Platform Control Overview';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        [$enabled, $disabled, $total] = $this->moduleCounts();

        return [
            Stat::make('Installed Modules', (string) $total)
                ->description($enabled . ' enabled / ' . $disabled . ' disabled')
                ->color($disabled > 0 ? 'warning' : 'success'),
            Stat::make('Users', $this->safeCount('users'))
                ->description('Platform accounts under Super Admin control')
                ->color('info'),
            Stat::make('Roles', $this->safeCount('roles'))
                ->description('Access policies managed through Shield')
                ->color('primary'),
            Stat::make('Themes', $this->themeCount())
                ->description('Installed theme packages and theme files')
                ->color('gray'),
            Stat::make('Panel Providers', (string) $this->panelProviderCount())
                ->description('Registered Filament panel configuration files')
                ->color('primary'),
            Stat::make('System Tables', (string) $this->tableCount())
                ->description('Current database schema visibility')
                ->color('gray'),
        ];
    }

    private function moduleCounts(): array
    {
        $modulesPath = base_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return [0, 0, 0];
        }

        $enabled = 0;
        $disabled = 0;

        foreach (File::directories($modulesPath) as $modulePath) {
            $enabledFile = $modulePath . DIRECTORY_SEPARATOR . 'module.json';
            $lockFile = $modulePath . DIRECTORY_SEPARATOR . 'module.lock.json';
            $enabledState = true;

            foreach ([$lockFile, $enabledFile] as $file) {
                if (! is_file($file)) {
                    continue;
                }

                $json = json_decode((string) file_get_contents($file), true) ?: [];

                if (array_key_exists('active', $json)) {
                    $enabledState = (bool) $json['active'];
                }

                if (array_key_exists('enabled', $json)) {
                    $enabledState = (bool) $json['enabled'];
                }
            }

            $enabledState ? $enabled++ : $disabled++;
        }

        return [$enabled, $disabled, $enabled + $disabled];
    }


    private function themeCount(): int|string
    {
        $paths = [
            base_path('themes'),
            resource_path('themes'),
            storage_path('app/public/themes'),
        ];

        $count = 0;

        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                $count += count(File::directories($path));
            }
        }

        return $count;
    }

    private function panelProviderCount(): int
    {
        return count(File::glob(app_path('Providers/Filament/*PanelProvider.php')) ?: []);
    }

    private function tableCount(): int|string
    {
        try {
            $connection = Schema::getConnection();
            return count($connection->getDoctrineSchemaManager()->listTableNames());
        } catch (\Throwable) {
            try {
                return count(Schema::getTables());
            } catch (\Throwable) {
                return '—';
            }
        }
    }

    private function safeCount(string $table): int|string
    {
        try {
            if (! Schema::hasTable($table)) {
                return '—';
            }

            return (string) \DB::table($table)->count();
        } catch (\Throwable) {
            return '—';
        }
    }
}
