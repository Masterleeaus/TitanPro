<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CleaningJobs\Filament\Resources\RecurringJobResource;

class EditRecurringJob extends EditRecord
{
    protected static string $resource = RecurringJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
