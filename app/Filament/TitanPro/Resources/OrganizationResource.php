<?php

namespace App\Filament\TitanPro\Resources;

use App\Filament\TitanPro\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Tenant Management';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Organizations';

    protected static ?string $modelLabel = 'Organization';

    protected static ?string $pluralModelLabel = 'Organizations';

    public static function canViewAny(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return static::isSuperAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canView(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isSuperAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::isSuperAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Organization')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('timezone')
                        ->required()
                        ->maxLength(120),
                    Select::make('plan')
                        ->options([
                            'starter' => 'Starter',
                            'growth' => 'Growth',
                            'pro' => 'Pro',
                        ])
                        ->required(),
                    DateTimePicker::make('trial_ends_at'),
                    DateTimePicker::make('suspended_at'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('plan')
                    ->badge()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users'),
                TextColumn::make('subscriptions_count')
                    ->counts('subscriptions')
                    ->label('Subscriptions'),
                TextColumn::make('suspended_at')
                    ->dateTime()
                    ->badge()
                    ->color(fn (mixed $state): string => $state ? 'danger' : 'success')
                    ->formatStateUsing(fn (mixed $state): string => $state ? 'Suspended' : 'Active'),
            ])
            ->filters([
                TernaryFilter::make('suspended_at')
                    ->label('Suspended')
                    ->nullable(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('suspend')
                    ->color('danger')
                    ->icon('heroicon-o-no-symbol')
                    ->visible(fn (Organization $record): bool => $record->suspended_at === null)
                    ->requiresConfirmation()
                    ->action(fn (Organization $record) => $record->update(['suspended_at' => now()])),
                Action::make('unsuspend')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Organization $record): bool => $record->suspended_at !== null)
                    ->requiresConfirmation()
                    ->action(fn (Organization $record) => $record->update(['suspended_at' => null])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount(['users', 'subscriptions']);
    }

    private static function isSuperAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
