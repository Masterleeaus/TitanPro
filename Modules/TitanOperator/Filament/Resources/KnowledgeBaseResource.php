<?php

namespace Modules\TitanOperator\Filament\Resources;

use Modules\TitanOperator\Models\KnowledgeBaseArticle;

if (class_exists(\Filament\Resources\Resource::class)) {
    class KnowledgeBaseResource extends \Filament\Resources\Resource
    {
        protected static ?string $model = KnowledgeBaseArticle::class;

        protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';

        protected static ?string $navigationLabel = 'Knowledge Base';

        public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
        {
            return $form->schema([
                \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(500),
                \Filament\Forms\Components\Textarea::make('description')->rows(2),
                \Filament\Forms\Components\Textarea::make('content')->rows(8),
                \Filament\Forms\Components\TextInput::make('operators.0')
                    ->label('Primary Operator ID')
                    ->numeric(),
                \Filament\Forms\Components\Toggle::make('is_featured')->default(false),
            ]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            return $table->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_featured')->boolean(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ]);
        }

        public static function getPages(): array
        {
            return [
                'index' => \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages\ListKnowledgeBaseArticles::route('/'),
                'create' => \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages\CreateKnowledgeBaseArticle::route('/create'),
                'edit' => \Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource\Pages\EditKnowledgeBaseArticle::route('/{record}/edit'),
            ];
        }
    }
} else {
    class KnowledgeBaseResource {}
}
