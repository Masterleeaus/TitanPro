<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Reporting';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.reports';
}
