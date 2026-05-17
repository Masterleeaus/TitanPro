<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingContent extends EditRecord
{
    protected static string $resource = TrainingContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
