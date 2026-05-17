<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class Verticals extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanNexus';

    protected static ?string $navigationLabel = 'Target Verticals';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Target Verticals';

    protected string $view = 'filament.titan-nexus.pages.verticals';
}
