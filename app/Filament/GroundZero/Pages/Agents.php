<?php

namespace App\Filament\GroundZero\Pages;

use Filament\Pages\Page;

class Agents extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';
    protected static string|\UnitEnum|null $navigationGroup = 'GroundZero';
    protected static ?string $navigationLabel = 'Agents';
    protected static ?string $title = 'GroundZero Agents';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'agents';

    protected string $view = 'filament.groundzero.pages.agents';
}
