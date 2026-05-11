<?php

namespace Modules\TitanEchoAssist\Filament\Resources\ConversationResource\Pages;

if (class_exists(\Filament\Resources\Pages\ViewRecord::class)) {
    class ViewConversation extends \Filament\Resources\Pages\ViewRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\ConversationResource::class;
    }
} else {
    class ViewConversation {}
}
