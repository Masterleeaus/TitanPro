<?php

namespace App\Filament\GroundZero\Pages;

use Filament\Pages\Page;

class Actions extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';
    protected static string|\UnitEnum|null $navigationGroup = 'GroundZero';
    protected static ?string $navigationLabel = 'Actions';
    protected static ?string $title = 'GroundZero Actions';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'actions';

    protected string $view = 'filament.groundzero.pages.actions';
}
