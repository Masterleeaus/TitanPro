<?php

namespace App\Filament\TitanNexus\Resources\VerticalPackResource\Pages;

use App\Filament\TitanNexus\Resources\VerticalPackResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVerticalPack extends ViewRecord
{
    protected static string $resource = VerticalPackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
