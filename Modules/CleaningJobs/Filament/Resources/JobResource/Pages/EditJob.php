<?php

namespace Modules\CleaningJobs\Filament\Resources\JobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CleaningJobs\Filament\Resources\JobResource;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
