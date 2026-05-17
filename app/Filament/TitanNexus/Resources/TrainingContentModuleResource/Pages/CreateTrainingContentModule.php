<?php

namespace App\Filament\TitanNexus\Resources\TrainingContentModuleResource\Pages;

use App\Filament\TitanNexus\Resources\TrainingContentModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingContentModule extends CreateRecord
{
    protected static string $resource = TrainingContentModuleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
