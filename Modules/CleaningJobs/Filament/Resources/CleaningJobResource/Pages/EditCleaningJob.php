<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\CleaningJobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CleaningJobs\Filament\Resources\CleaningJobResource;

class EditCleaningJob extends EditRecord
{
    protected static string $resource = CleaningJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
