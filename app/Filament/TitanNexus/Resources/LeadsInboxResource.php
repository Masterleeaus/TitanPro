<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadsInboxResource\Pages;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanLeads\Models\MarketingConversation;

class LeadsInboxResource extends Resource
{
    protected static ?string $model = MarketingConversation::class;

    protected static ?string $slug = 'leads-inbox';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Leads Inbox';

    protected static ?string $navigationGroup = 'Titan Leads';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Conversation')
                ->schema([
                    TextInput::make('conversation_name')->maxLength(255),
                    Select::make('type')
                        ->options([
                            'whatsapp'  => 'WhatsApp',
                            'sms'       => 'SMS',
                            'telegram'  => 'Telegram',
                            'messenger' => 'Messenger',
                            'voice'     => 'Voice',
                            'webchat'   => 'Web Chat',
                        ]),
                    TextInput::make('ip_address')->maxLength(45),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('last_activity_at', 'desc')
            ->columns([
                TextColumn::make('conversation_name')->label('Lead / Name')->searchable()->sortable(),
                TextColumn::make('type')->label('Channel')->badge()->sortable(),
                TextColumn::make('lastMessage.message')->label('Last Message')->limit(60),
                TextColumn::make('last_activity_at')->label('Last Activity')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->hasRole(['owner', 'admin']);
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLeadsInbox::route('/'),
            'create' => Pages\CreateLeadsInbox::route('/create'),
            'view'   => Pages\ViewLeadsInbox::route('/{record}'),
            'edit'   => Pages\EditLeadsInbox::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $userId = auth()->id();

        if ($userId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()->where('user_id', $userId);
    }
}
