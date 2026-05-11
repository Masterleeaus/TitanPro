<?php

namespace App\Filament\TitanQuotes\Resources\EstimateResource\Pages;

use App\Filament\TitanQuotes\Resources\EstimateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstimates extends ListRecords
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
