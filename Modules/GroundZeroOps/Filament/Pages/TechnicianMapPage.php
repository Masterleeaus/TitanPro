<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;

class TechnicianMapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Technician Map';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'groundzeroops::pages.technicianmappage';

    public function getTitle(): string
    {
        return 'Technician Map';
    }
}
