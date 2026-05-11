<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingContentModule extends ViewRecord
{
    protected static string $resource = TrainingContentModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
