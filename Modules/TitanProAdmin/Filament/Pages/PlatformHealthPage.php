<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanProAdmin\Policies\SuperAdminPolicy;
use Modules\TitanProAdmin\Services\PlatformHealthService;

class PlatformHealthPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Platform Health';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'titanproadmin::pages.platform-health';

    public function getTitle(): string
    {
        return 'Platform Health';
    }

    public static function canAccess(): bool
    {
        $user = auth('super_admin')->user() ?? auth()->user();

        return app(SuperAdminPolicy::class)->access($user);
    }

    public function health(): array
    {
        return app(PlatformHealthService::class)->status();
    }
}
