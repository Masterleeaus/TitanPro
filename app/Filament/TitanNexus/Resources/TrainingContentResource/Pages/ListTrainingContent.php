<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrainingContent extends ListRecords
{
    protected static string $resource = TrainingContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
