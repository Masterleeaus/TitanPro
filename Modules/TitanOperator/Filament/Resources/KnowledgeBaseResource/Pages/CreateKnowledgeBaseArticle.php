<?php

namespace Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages;

if (class_exists(\Filament\Resources\Pages\CreateRecord::class)) {
    class CreateKnowledgeBaseArticle extends \Filament\Resources\Pages\CreateRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
        {
            $data['user_id'] ??= auth()->id();
            $data['operators'] = array_values(array_filter((array) ($data['operators'] ?? [])));

            return $data;
        }
    }
} else {
    class CreateKnowledgeBaseArticle {}
}
