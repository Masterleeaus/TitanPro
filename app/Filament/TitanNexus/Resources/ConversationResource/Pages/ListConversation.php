<?php

namespace App\Filament\TitanNexus\Resources\ConversationResource\Pages;

use App\Filament\TitanNexus\Resources\ConversationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConversation extends ListRecords
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
