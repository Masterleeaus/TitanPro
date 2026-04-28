<?php

namespace Modules\CRMCore\Filament\Resources\DealProjectResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CRMCore\Filament\Resources\DealProjectResource;

class ListDealProjects extends ListRecords
{
    protected static string $resource = DealProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Deal'),
        ];
    }
}
