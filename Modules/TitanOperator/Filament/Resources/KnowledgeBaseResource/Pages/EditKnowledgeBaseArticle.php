<?php

namespace Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditKnowledgeBaseArticle extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource::class;
    }
} else {
    class EditKnowledgeBaseArticle {}
}
