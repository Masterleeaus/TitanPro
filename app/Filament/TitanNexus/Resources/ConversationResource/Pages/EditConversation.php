<?php

namespace App\Filament\TitanNexus\Resources\ConversationResource\Pages;

use App\Filament\TitanNexus\Resources\ConversationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConversation extends EditRecord
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
