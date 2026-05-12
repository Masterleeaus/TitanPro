<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;

class ShiftManagerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Shift Manager';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'groundzeroops::pages.shiftmanagerpage';

    public function getTitle(): string
    {
        return 'Shift Manager';
    }
}
