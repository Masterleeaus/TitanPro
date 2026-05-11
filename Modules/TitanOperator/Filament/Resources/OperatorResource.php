<?php

namespace Modules\TitanOperator\Filament\Resources;

use Illuminate\Support\Str;
use Modules\TitanOperator\Models\Operator;

if (class_exists(\Filament\Resources\Resource::class)) {
    class OperatorResource extends \Filament\Resources\Resource
    {
        protected static ?string $model = Operator::class;

        protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';

        protected static ?string $navigationLabel = 'Operators';

        public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
        {
            return $form->schema([
                \Filament\Forms\Components\TextInput::make('title')->required()->maxLength(255),
                \Filament\Forms\Components\TextInput::make('ai_model')->required()->maxLength(255)->default('gpt-4.1'),
                \Filament\Forms\Components\TextInput::make('ai_embedding_model')->required()->maxLength(255)->default('text-embedding-3-small'),
                \Filament\Forms\Components\Textarea::make('instructions')->rows(4),
                \Filament\Forms\Components\Toggle::make('active')->default(true),
            ]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            return $table->columns([
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('ai_model')->searchable(),
                \Filament\Tables\Columns\IconColumn::make('active')->boolean(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ]);
        }

        public static function mutateFormDataBeforeCreate(array $data): array
        {
            $data['uuid'] ??= (string) Str::uuid();
            $data['user_id'] ??= auth()->id() ?? 0;

            return $data;
        }

        public static function getPages(): array
        {
            return [
                'index' => \Modules\TitanOperator\Filament\Resources\OperatorResource\Pages\ListOperators::route('/'),
                'create' => \Modules\TitanOperator\Filament\Resources\OperatorResource\Pages\CreateOperator::route('/create'),
                'edit' => \Modules\TitanOperator\Filament\Resources\OperatorResource\Pages\EditOperator::route('/{record}/edit'),
            ];
        }
    }
} else {
    class OperatorResource {}
}
