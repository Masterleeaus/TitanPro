<?php

namespace Modules\TitanRewind\Filament\Resources\RewindEventResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanRewind\Filament\Resources\RewindEventResource;

class ListRewindEvents extends ListRecords
{
    protected static string $resource = RewindEventResource::class;

    public static function canAccess(): bool
    {
        return RewindEventResource::canViewAny();
    }
}
