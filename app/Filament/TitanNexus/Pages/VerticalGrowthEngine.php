<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class VerticalGrowthEngine extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'Growth Engine';

    protected static ?string $title = 'TitanNexus Growth Engine';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.titan-nexus.pages.vertical-growth-engine';
}
