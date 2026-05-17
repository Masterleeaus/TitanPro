<?php

namespace Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages;

if (class_exists(\Filament\Resources\Pages\ListRecords::class)) {
    class ListKnowledgeBaseArticles extends \Filament\Resources\Pages\ListRecords
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource::class;
    }
} else {
    class ListKnowledgeBaseArticles {}
}
