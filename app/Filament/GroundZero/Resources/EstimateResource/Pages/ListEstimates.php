<?php

namespace App\Filament\GroundZero\Resources\EstimateResource\Pages;

use App\Filament\GroundZero\Resources\EstimateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstimates extends ListRecords
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
