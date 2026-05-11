<?php

namespace Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages;

if (class_exists(\Filament\Resources\Pages\EditRecord::class)) {
    class EditKnowledgeBaseArticle extends \Filament\Resources\Pages\EditRecord
    {
        protected static string $resource = \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource::class;

        protected function mutateFormDataBeforeSave(array $data): array
        {
            $data['operators'] = array_values(array_filter((array) ($data['operators'] ?? [])));

            return $data;
        }
    }
} else {
    class EditKnowledgeBaseArticle {}
}
