<?php

namespace App\Filament\TitanSolo\Resources\JobResource\Pages;

use App\Filament\TitanSolo\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
