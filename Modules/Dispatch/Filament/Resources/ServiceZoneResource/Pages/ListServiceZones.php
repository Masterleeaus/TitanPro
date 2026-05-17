<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ServiceZoneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\ServiceZoneResource;

class ListServiceZones extends ListRecords
{
    protected static string $resource = ServiceZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
