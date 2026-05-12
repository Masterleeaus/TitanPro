<?php

namespace Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CleaningJobs\Filament\Resources\RecurringJobResource;

class ListRecurringJobs extends ListRecords
{
    protected static string $resource = RecurringJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
