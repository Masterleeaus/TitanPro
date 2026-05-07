<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class Verticals extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Verticals';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Vertical Packs';

    protected string $view = 'filament.titan-nexus.pages.verticals';
}
