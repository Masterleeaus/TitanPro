<?php

namespace Modules\CleaningJobs\Filament\Resources\JobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CleaningJobs\Filament\Resources\JobResource;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
