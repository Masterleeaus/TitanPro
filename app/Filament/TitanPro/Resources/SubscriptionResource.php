<?php

namespace App\Filament\TitanPro\Resources;

use App\Filament\TitanPro\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Billing & Subscriptions';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Subscriptions';

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
            Section::make('Subscription')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    Select::make('organization_id')
                        ->label('Organization')
                        ->relationship('organization', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('plan')
                        ->options([
                            'starter' => 'Starter',
                            'growth' => 'Growth',
                            'pro' => 'Pro',
                        ])
                        ->required(),
                    Select::make('status')
                        ->options([
                            Subscription::STATUS_TRIALING => 'Trialing',
                            Subscription::STATUS_ACTIVE => 'Active',
                            Subscription::STATUS_PAST_DUE => 'Past Due',
                            Subscription::STATUS_CANCELED => 'Canceled',
                            Subscription::STATUS_PAUSED => 'Paused',
                        ])
                        ->required(),
                    Select::make('billing_interval')
                        ->options([
                            'monthly' => 'Monthly',
                            'annual' => 'Annual',
                        ])
                        ->required(),
                    TextInput::make('stripe_subscription_id')->maxLength(255),
                    TextInput::make('stripe_price_id')->maxLength(255),
                    DateTimePicker::make('trial_ends_at'),
                    DateTimePicker::make('current_period_start'),
                    DateTimePicker::make('current_period_end'),
                    DateTimePicker::make('canceled_at'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization.name')
                    ->label('Organization')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Subscription::STATUS_ACTIVE => 'success',
                        Subscription::STATUS_TRIALING => 'info',
                        Subscription::STATUS_PAST_DUE => 'warning',
                        Subscription::STATUS_CANCELED => 'danger',
                        Subscription::STATUS_PAUSED => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('plan')
                    ->badge()
                    ->sortable(),
                TextColumn::make('billing_interval')
                    ->sortable(),
                TextColumn::make('current_period_end')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('organization_id')
                    ->label('Organization')
                    ->relationship('organization', 'name'),
                SelectFilter::make('status')
                    ->options([
                        Subscription::STATUS_TRIALING => 'Trialing',
                        Subscription::STATUS_ACTIVE => 'Active',
                        Subscription::STATUS_PAST_DUE => 'Past Due',
                        Subscription::STATUS_CANCELED => 'Canceled',
                        Subscription::STATUS_PAUSED => 'Paused',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('organization');
    }

    private static function isSuperAdmin(): bool
    {
        return (bool) auth()->user()?->hasRole('super_admin');
    }
}
