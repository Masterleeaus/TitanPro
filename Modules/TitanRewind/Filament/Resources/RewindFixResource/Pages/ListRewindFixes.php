<?php

namespace Modules\TitanRewind\Filament\Resources\RewindFixResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanRewind\Filament\Resources\RewindFixResource;

class ListRewindFixes extends ListRecords
{
    protected static string $resource = RewindFixResource::class;

    public static function canAccess(): bool
    {
        return RewindFixResource::canViewAny();
    }
}
