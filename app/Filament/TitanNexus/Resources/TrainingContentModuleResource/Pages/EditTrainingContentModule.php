<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingContentModule extends EditRecord
{
    protected static string $resource = TrainingContentModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
