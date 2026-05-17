<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class LeadPipeline extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanNexus';

    protected static ?string $navigationLabel = 'Lead Pipeline';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Lead Pipeline';

    protected string $view = 'filament.titan-nexus.pages.lead-pipeline';
}
