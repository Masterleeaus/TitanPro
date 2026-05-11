<?php

namespace App\Filament\GroundZero\Resources\EstimateResource\Pages;

use App\Filament\GroundZero\Resources\EstimateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEstimate extends EditRecord
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
