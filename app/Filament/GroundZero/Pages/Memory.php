<?php

namespace App\Filament\GroundZero\Pages;

use Filament\Pages\Page;

class Memory extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-circle-stack';
    protected static string|\UnitEnum|null $navigationGroup = 'GroundZero';
    protected static ?string $navigationLabel = 'Memory';
    protected static ?string $title = 'GroundZero Memory';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'memory';

    protected string $view = 'filament.groundzero.pages.memory';
}
