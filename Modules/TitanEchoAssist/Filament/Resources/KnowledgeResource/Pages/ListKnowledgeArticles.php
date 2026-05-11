<?php

namespace Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource\Pages;

if (class_exists(\Filament\Resources\Pages\ListRecords::class)) {
    class ListKnowledgeArticles extends \Filament\Resources\Pages\ListRecords
    {
        protected static string $resource = \Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource::class;
    }
} else {
    class ListKnowledgeArticles {}
}
