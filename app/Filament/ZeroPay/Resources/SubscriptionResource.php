<?php

namespace App\Filament\ZeroPay\Resources;

use App\Filament\ZeroPay\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Subscriptions';

    protected static ?string $modelLabel = 'Subscription';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscription')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'starter' => 'Starter',
                            'growth'  => 'Growth',
                            'pro'     => 'Pro',
                        ])
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            Subscription::STATUS_TRIALING  => 'Trialing',
                            Subscription::STATUS_ACTIVE    => 'Active',
                            Subscription::STATUS_PAST_DUE => 'Past Due',
                            Subscription::STATUS_CANCELED  => 'Canceled',
                            Subscription::STATUS_PAUSED    => 'Paused',
                        ])
                        ->required(),
                    Select::make('billing_interval')
                        ->label('Billing Interval')
                        ->options([
                            'monthly' => 'Monthly',
                            'annual'  => 'Annual',
                        ])
                        ->required(),
                    TextInput::make('stripe_subscription_id')
                        ->label('Stripe Subscription ID')
                        ->maxLength(255),
                    TextInput::make('stripe_price_id')
                        ->label('Stripe Price ID')
                        ->maxLength(255),
                    DateTimePicker::make('trial_ends_at')
                        ->label('Trial Ends At'),
                    DateTimePicker::make('current_period_start')
                        ->label('Current Period Start'),
                    DateTimePicker::make('current_period_end')
                        ->label('Current Period End'),
                    DateTimePicker::make('canceled_at')
                        ->label('Canceled At'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Subscription::STATUS_ACTIVE    => 'success',
                        Subscription::STATUS_TRIALING  => 'info',
                        Subscription::STATUS_PAST_DUE => 'warning',
                        Subscription::STATUS_CANCELED  => 'danger',
                        Subscription::STATUS_PAUSED    => 'gray',
                        default                        => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->sortable(),
                TextColumn::make('billing_interval')
                    ->label('Interval')
                    ->sortable(),
                TextColumn::make('current_period_start')
                    ->label('Period Start')
                    ->date()
                    ->sortable(),
                TextColumn::make('current_period_end')
                    ->label('Period End')
                    ->date()
                    ->sortable(),
                TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubscriptions::route('/'),
            'view'   => Pages\ViewSubscription::route('/{record}'),
            'edit'   => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('organization_id', $organizationId);
    }
}
