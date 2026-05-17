<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchRouteStopResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchRouteStopResource;

class ListDispatchRouteStops extends ListRecords
{
    protected static string $resource = DispatchRouteStopResource::class;
    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }

}
