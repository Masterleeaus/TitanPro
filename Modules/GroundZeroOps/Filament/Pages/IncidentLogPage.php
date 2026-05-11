<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;

class IncidentLogPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Incident Log';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'groundzeroops::pages.incidentlogpage';

    public function getTitle(): string
    {
        return 'Incident Log';
    }
}
