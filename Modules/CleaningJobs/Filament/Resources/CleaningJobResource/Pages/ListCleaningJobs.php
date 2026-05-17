<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\CleaningJobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\CleaningJobs\Filament\Resources\CleaningJobResource;

class ListCleaningJobs extends ListRecords
{
    protected static string $resource = CleaningJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
