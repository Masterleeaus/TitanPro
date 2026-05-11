<?php

namespace Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateKnowledgeArticle extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource::class;
    }
} else {
    class CreateKnowledgeArticle {}
}
