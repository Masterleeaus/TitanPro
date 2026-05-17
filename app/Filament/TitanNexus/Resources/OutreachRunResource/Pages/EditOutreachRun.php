<?php

namespace App\Filament\TitanNexus\Resources\OutreachRunResource\Pages;

use App\Filament\TitanNexus\Resources\OutreachRunResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOutreachRun extends EditRecord
{
    protected static string $resource = OutreachRunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
