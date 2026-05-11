<?php

namespace App\Filament\GroundZero\Resources\JobResource\Pages;

use App\Filament\GroundZero\Resources\JobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create Job'),
        ];
    }
}
