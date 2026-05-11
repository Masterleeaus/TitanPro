<?php

namespace Modules\TitanOperator\Filament\Resources;

use Modules\TitanOperator\Models\Channel;

if (class_exists(\Filament\Resources\Resource::class)) {
    class ChannelResource extends \Filament\Resources\Resource
    {
        protected static ?string $model = Channel::class;

        protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-link';

        protected static ?string $navigationLabel = 'Operator Channels';

        public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
        {
            return $form->schema([
                \Filament\Forms\Components\Select::make('operator_id')
                    ->relationship('operator', 'title')
                    ->required(),
                \Filament\Forms\Components\Select::make('channel')
                    ->options([
                        'web' => 'Web',
                        'whatsapp' => 'WhatsApp',
                        'telegram' => 'Telegram',
                        'messenger' => 'Messenger',
                    ])
                    ->required(),
                \Filament\Forms\Components\KeyValue::make('credentials'),
            ]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            return $table->columns([
                \Filament\Tables\Columns\TextColumn::make('operator.title')->label('Operator')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('channel')->badge()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('connected_at')->dateTime(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ]);
        }

        public static function mutateFormDataBeforeCreate(array $data): array
        {
            $data['user_id'] ??= auth()->id() ?? 0;

            return $data;
        }

        public static function getPages(): array
        {
            return [
                'index' => \Modules\TitanOperator\Filament\Resources\ChannelResource\Pages\ListChannels::route('/'),
                'create' => \Modules\TitanOperator\Filament\Resources\ChannelResource\Pages\CreateChannel::route('/create'),
                'edit' => \Modules\TitanOperator\Filament\Resources\ChannelResource\Pages\EditChannel::route('/{record}/edit'),
            ];
        }
    }
} else {
    class ChannelResource {}
}
