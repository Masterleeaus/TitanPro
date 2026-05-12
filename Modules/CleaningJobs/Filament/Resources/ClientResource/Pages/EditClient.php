<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\ClientResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CleaningJobs\Filament\Resources\ClientResource;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
