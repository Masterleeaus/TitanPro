<?php

namespace Modules\TitanOperator\Filament\Resources;

use Modules\TitanOperator\Models\Conversation;

if (class_exists(\Filament\Resources\Resource::class)) {
    class ConversationResource extends \Filament\Resources\Resource
    {
        protected static ?string $model = Conversation::class;

        protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

        protected static ?string $navigationLabel = 'Operator Conversations';

        public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
        {
            return $form->schema([]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            return $table->columns([
                \Filament\Tables\Columns\TextColumn::make('operator.title')->label('Operator')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('session_id')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('last_activity_at')->dateTime()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ]);
        }

        public static function getPages(): array
        {
            return [
                'index' => \Modules\TitanOperator\Filament\Resources\ConversationResource\Pages\ListConversations::route('/'),
            ];
        }
    }
} else {
    class ConversationResource {}
}
