<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchRouteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchRouteResource;

class ListDispatchRoutes extends ListRecords
{
    protected static string $resource = DispatchRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
