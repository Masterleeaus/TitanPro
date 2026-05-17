<?php

namespace Modules\CRMCore\Filament\Resources\DealResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CRMCore\Filament\Resources\DealResource;

class ListDeals extends ListRecords
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
