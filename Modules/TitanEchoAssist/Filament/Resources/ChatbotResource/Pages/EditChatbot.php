<?php

namespace Modules\TitanEchoAssist\Filament\Resources\ChatbotResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditChatbot extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\ChatbotResource::class;
    }
} else {
    class EditChatbot {}
}
