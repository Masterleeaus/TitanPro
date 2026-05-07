<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;

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
}
