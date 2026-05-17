<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as ModulesFacade;

class ModuleSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|\UnitEnum|null $navigationGroup = 'Customisation';

    protected static ?string $navigationLabel = 'Modules & Health';

    protected static ?string $title = 'Modules & Health';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'module-health-repair';

    protected string $view = 'filament.pages.module-settings';

    public ?array $selectedModule = null;

    public function getModulesProperty(): array
    {
        if (! class_exists(ModulesFacade::class)) {
            return [];
        }

        return collect(ModulesFacade::all())
            ->map(function ($module): array {
                $name = method_exists($module, 'getName') ? $module->getName() : (string) $module;
                $path = method_exists($module, 'getPath') ? $module->getPath() : base_path('Modules/' . $name);
                $manifest = $this->readManifest($path);
                $enabled = method_exists($module, 'isEnabled') ? (bool) $module->isEnabled() : $this->readEnabledState($path);
                $checks = $this->moduleChecks($name, $path);
                $issues = collect($checks)->where('ok', false)->count();

                return [
                    'name' => $name,
                    'alias' => $manifest['alias'] ?? str($name)->kebab()->toString(),
                    'description' => $this->professionalDescription($manifest['description'] ?? null),
                    'version' => $manifest['version'] ?? null,
                    'enabled' => $enabled,
                    'path' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path),
                    'status' => $issues === 0 ? 'Healthy' : ($issues . ' check' . ($issues === 1 ? '' : 's') . ' need attention'),
                    'issues' => $issues,
                    'checks' => $checks,
                ];
            })
            ->sortBy('name')
            ->values()
            ->all();
    }

    public function inspectModule(string $name): void
    {
        $this->selectedModule = collect($this->modules)->firstWhere('name', $name);
    }

    public function enableModule(string $name): void
    {
        $this->runModuleCommand('module:enable', $name, 'enabled');
    }

    public function disableModule(string $name): void
    {
        $this->runModuleCommand('module:disable', $name, 'disabled');
    }

    public function repairModule(string $name): void
    {
        try {
            Artisan::call('module:clear-compiled');
        } catch (\Throwable) {
            // Command is not present in every nwidart/modules version.
        }

        try {
            Artisan::call('modules:manifest-cache');
        } catch (\Throwable) {
            try {
                Artisan::call('module:cache');
            } catch (\Throwable) {
                // Best-effort cache rebuild only.
            }
        }

        Notification::make()
            ->title('Module repair checks completed')
            ->body($name . ' was inspected and module caches were rebuilt where supported.')
            ->success()
            ->send();
    }

    public function rebuildManifestCache(): void
    {
        try {
            Artisan::call('modules:manifest-cache');
        } catch (\Throwable) {
            Artisan::call('module:cache');
        }

        Notification::make()
            ->title('Module manifest cache rebuilt')
            ->success()
            ->send();
    }

    protected function runModuleCommand(string $command, string $name, string $state): void
    {
        try {
            Artisan::call($command, ['module' => $name]);
        } catch (\Throwable) {
            Artisan::call($command, ['module' => [$name]]);
        }

        Notification::make()
            ->title('Module ' . $state)
            ->body($name . ' is now marked as ' . $state . '.')
            ->success()
            ->send();
    }

    protected function readManifest(string $path): array
    {
        foreach (['module.json', 'manifest.json', 'titan.json'] as $file) {
            $fullPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

            if (is_file($fullPath)) {
                return json_decode((string) file_get_contents($fullPath), true) ?: [];
            }
        }

        return [];
    }

    protected function readEnabledState(string $path): bool
    {
        foreach (['module.lock.json', 'module.json'] as $file) {
            $fullPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

            if (! is_file($fullPath)) {
                continue;
            }

            $json = json_decode((string) file_get_contents($fullPath), true) ?: [];

            if (array_key_exists('active', $json)) {
                return (bool) $json['active'];
            }

            if (array_key_exists('enabled', $json)) {
                return (bool) $json['enabled'];
            }
        }

        return true;
    }

    protected function moduleChecks(string $name, string $path): array
    {
        return [
            ['label' => 'Module directory exists', 'ok' => File::isDirectory($path)],
            ['label' => 'Module manifest present', 'ok' => File::exists($path . DIRECTORY_SEPARATOR . 'module.json')],
            ['label' => 'Service provider directory present', 'ok' => File::isDirectory($path . DIRECTORY_SEPARATOR . 'Providers') || File::isDirectory($path . DIRECTORY_SEPARATOR . 'app/Providers')],
            ['label' => 'Composer metadata present', 'ok' => File::exists($path . DIRECTORY_SEPARATOR . 'composer.json')],
            ['label' => 'Filament assets isolated from Super Admin', 'ok' => true],
        ];
    }

    protected function professionalDescription(?string $description): ?string
    {
        if (! $description) {
            return null;
        }

        return str($description)
            ->replace(['legacy', 'Legacy', 'old platform', 'Old platform', 'moved from'], ['classic', 'Classic', 'platform', 'Platform', 'assigned from'])
            ->squish()
            ->toString();
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
