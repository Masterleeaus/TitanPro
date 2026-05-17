<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingContentModules extends ListRecords
{
    protected static string $resource = TrainingContentModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
