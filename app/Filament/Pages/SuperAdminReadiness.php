<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class SuperAdminReadiness extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Customisation';

    protected static ?string $navigationLabel = 'Feature Health Audit';

    protected static ?string $title = 'Feature Health Audit';

    protected static ?int $navigationSort = 90;

    protected static ?string $slug = 'super-admin-readiness';

    protected string $view = 'filament.pages.super-admin-readiness';

    public function getChecksProperty(): array
    {
        return [
            $this->check('Admin panel mounted', url('/admin'), Route::has('filament.admin.pages.dashboard')),
            $this->check('Titan Pro panel mounted at /pro', url('/pro'), (bool) collect(Route::getRoutes())->first(fn ($route) => str_starts_with((string) $route->uri(), 'pro'))),
                        $this->check('Site settings page discovered', '/admin/site-settings', class_exists(\App\Filament\Pages\SiteSettings::class)),
            $this->check('Theme manager page discovered', '/admin/theme-manager', class_exists(\App\Filament\Pages\ThemeManager::class)),
                        $this->check('Module settings page installed', '/admin/module-settings', class_exists(\App\Filament\Pages\ModuleSettings::class)),
            $this->check('Panel settings page installed', '/admin/panel-settings', class_exists(\App\Filament\Admin\Pages\PanelLinks::class)),
            $this->check('Module directory exists', base_path('Modules'), File::isDirectory(base_path('Modules'))),
            $this->check('Titan panel registry exists', config_path('titan_panels.php'), File::exists(config_path('titan_panels.php'))),
        ];
    }

    protected function check(string $label, string $target, bool $ok): array
    {
        return compact('label', 'target', 'ok');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
