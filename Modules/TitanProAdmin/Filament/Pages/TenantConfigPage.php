<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;

class TenantConfigPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Tenant Config';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'titanproadmin::pages.tenant-config';

    public function getTitle(): string
    {
        return 'Tenant Configuration';
    }
}
