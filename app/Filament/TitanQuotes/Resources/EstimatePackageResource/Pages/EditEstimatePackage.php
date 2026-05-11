<?php

namespace App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages;

use App\Filament\TitanQuotes\Resources\EstimatePackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstimatePackage extends EditRecord
{
    protected static string $resource = EstimatePackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
