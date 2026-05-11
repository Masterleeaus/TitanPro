<?php

namespace App\Filament\TitanNexus\Resources\VerticalPackResource\Pages;

use App\Filament\TitanNexus\Resources\VerticalPackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerticalPacks extends ListRecords
{
    protected static string $resource = VerticalPackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
