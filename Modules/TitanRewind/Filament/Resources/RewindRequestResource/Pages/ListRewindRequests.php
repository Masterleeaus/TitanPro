<?php

namespace Modules\TitanRewind\Filament\Resources\RewindRequestResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanRewind\Filament\Resources\RewindRequestResource;

class ListRewindRequests extends ListRecords
{
    protected static string $resource = RewindRequestResource::class;

    public static function canAccess(): bool
    {
        return RewindRequestResource::canViewAny();
    }
}
