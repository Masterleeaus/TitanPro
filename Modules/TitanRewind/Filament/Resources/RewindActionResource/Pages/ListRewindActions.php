<?php

namespace Modules\TitanRewind\Filament\Resources\RewindActionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanRewind\Filament\Resources\RewindActionResource;

class ListRewindActions extends ListRecords
{
    protected static string $resource = RewindActionResource::class;

    public static function canAccess(): bool
    {
        return RewindActionResource::canViewAny();
    }
}
