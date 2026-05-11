<?php

namespace Modules\TitanRewind\Filament\Resources\RewindCaseResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanRewind\Filament\Resources\RewindCaseResource;

class ListRewindCases extends ListRecords
{
    protected static string $resource = RewindCaseResource::class;

    public static function canAccess(): bool
    {
        return RewindCaseResource::canViewAny();
    }
}
