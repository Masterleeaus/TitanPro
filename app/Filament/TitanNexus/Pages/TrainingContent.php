<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class TrainingContent extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Delivery Readiness';

    protected static ?string $navigationLabel = 'Training Content';

    protected static ?int $navigationSort = 42;

    protected static ?string $title = 'Training Content';

    protected string $view = 'filament.titan-nexus.pages.training-content';
}
