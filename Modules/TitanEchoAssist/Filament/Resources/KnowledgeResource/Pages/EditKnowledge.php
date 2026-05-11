<?php

namespace Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditKnowledge extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource::class;
    }
} else {
    class EditKnowledge {}
}
