<?php

namespace App\Filament\GroundZero\Pages;

use Filament\Pages\Page;

class Timeline extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bolt';
    protected static string|\UnitEnum|null $navigationGroup = 'GroundZero';
    protected static ?string $navigationLabel = 'Timeline';
    protected static ?string $title = 'GroundZero Timeline';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'timeline';

    protected string $view = 'filament.groundzero.pages.timeline';
}
