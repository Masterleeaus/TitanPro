<?php

namespace Modules\TitanOperator\Filament\Resources\ConversationResource\Pages;

if (class_exists(\Filament\Resources\Pages\ListRecords::class)) {
    class ListConversations extends \Filament\Resources\Pages\ListRecords
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\ConversationResource::class;
    }
} else {
    class ListConversations {}
}
