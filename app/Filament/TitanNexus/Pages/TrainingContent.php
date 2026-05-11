<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class TrainingContent extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Training Content';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.titan-nexus.pages.training-content';
}
