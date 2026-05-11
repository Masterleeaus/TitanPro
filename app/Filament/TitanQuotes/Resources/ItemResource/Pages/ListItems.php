<?php

namespace App\Filament\TitanQuotes\Resources\ItemResource\Pages;

use App\Filament\TitanQuotes\Resources\ItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
