<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\ConversationResource\Pages;
use App\Models\TitanNexus\NexusConversation;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConversationResource extends Resource
{
    protected static ?string $model = NexusConversation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Conversation Inbox';

    protected static ?string $modelLabel = 'Conversation';

    protected static ?string $pluralModelLabel = 'Conversation Inbox';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('contact_id')->label('Contact ID')->numeric(),
                TextInput::make('channel')->label('Channel')->maxLength(255),
                Select::make('status')->label('Status')->options(['draft'=>'Draft','new'=>'New','active'=>'Active','pending'=>'Pending','contacted'=>'Contacted','qualified'=>'Qualified','booked'=>'Booked','closed'=>'Closed','inactive'=>'Inactive'])->default('new'),
                DateTimePicker::make('last_message_at')->label('Last Message At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('contact_id')->label('Contact Id')->searchable()->sortable()->toggleable(),
                TextColumn::make('channel')->label('Channel')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->badge()->searchable()->sortable(),
                TextColumn::make('last_message_at')->label('Last Message At')->dateTime()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversation::route('/'),
            'create' => Pages\CreateConversation::route('/create'),
            'edit' => Pages\EditConversation::route('/{record}/edit'),
        ];
    }
}
