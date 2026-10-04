<?php

namespace Modules\TitanSoloDash\Filament\Pages;

use Filament\Pages\Page;

class QuickCreatePage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationLabel = 'Quick Create';
    protected static string|\UnitEnum|null $navigationGroup = 'Command Centre';
    protected static ?int $navigationSort = 20;
    protected string $view = 'titansolodash::pages.quickcreatepage';

    public function getTitle(): string
    {
        return 'Quick Create';
    }
}
