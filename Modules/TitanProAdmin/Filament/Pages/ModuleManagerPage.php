<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;

class ModuleManagerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationLabel = 'Module Manager';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'titanproadmin::pages.module-manager';

    public function getTitle(): string
    {
        return 'Module Manager';
    }
}
