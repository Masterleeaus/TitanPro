<?php

namespace Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateKnowledgeBaseArticle extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource::class;
    }
} else {
    class CreateKnowledgeBaseArticle {}
}
