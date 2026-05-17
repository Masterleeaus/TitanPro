<?php

namespace App\Filament\TitanNexus\Resources\TargetVerticalResource\Pages;

use App\Filament\TitanNexus\Resources\TargetVerticalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTargetVertical extends EditRecord
{
    protected static string $resource = TargetVerticalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
