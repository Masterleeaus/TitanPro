<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\CustomerLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\CustomerLocationResource;

class ListCustomerLocations extends ListRecords
{
    protected static string $resource = CustomerLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
