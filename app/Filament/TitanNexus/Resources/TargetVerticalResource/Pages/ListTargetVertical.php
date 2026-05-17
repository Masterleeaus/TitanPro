<?php

namespace App\Filament\TitanNexus\Resources\TargetVerticalResource\Pages;

use App\Filament\TitanNexus\Resources\TargetVerticalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTargetVertical extends ListRecords
{
    protected static string $resource = TargetVerticalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
