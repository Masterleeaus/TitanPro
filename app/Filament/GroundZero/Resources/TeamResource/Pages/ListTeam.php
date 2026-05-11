<?php

namespace App\Filament\GroundZero\Resources\TeamResource\Pages;

use App\Filament\GroundZero\Resources\TeamResource;
use Filament\Resources\Pages\ListRecords;

class ListTeam extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
