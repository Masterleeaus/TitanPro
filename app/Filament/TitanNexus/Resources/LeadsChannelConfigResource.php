<?php

namespace App\Filament\TitanNexus\Resources;

use App\Filament\TitanNexus\Resources\LeadsChannelConfigResource\Pages;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\TitanLeads\Models\SmsChannel;

class LeadsChannelConfigResource extends Resource
{
    protected static ?string $model = SmsChannel::class;

    protected static ?string $slug = 'leads-channels';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationLabel = 'Channel Config';

    protected static ?string $navigationGroup = 'Titan Leads';

    protected static ?int $navigationSort = 53;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('SMS / Voice Channel')
                ->schema([
                    Select::make('provider')
                        ->options([
                            'twilio'  => 'Twilio',
                            'vonage'  => 'Vonage',
                            'plivo'   => 'Plivo',
                        ])
                        ->default('twilio'),
                    TextInput::make('account_sid')->label('Account SID')->maxLength(255),
                    TextInput::make('auth_token')->label('Auth Token')->password()->maxLength(255),
                    TextInput::make('from_number')->label('From Number')->tel()->maxLength(50),
                    Toggle::make('is_active')->label('Active')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('provider')->badge()->sortable(),
                TextColumn::make('from_number')->label('From Number')->searchable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index'  => Pages\ListLeadsChannelConfigs::route('/'),
            'create' => Pages\CreateLeadsChannelConfig::route('/create'),
            'view'   => Pages\ViewLeadsChannelConfig::route('/{record}'),
            'edit'   => Pages\EditLeadsChannelConfig::route('/{record}/edit'),
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
