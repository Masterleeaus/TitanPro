<?php

namespace App\Filament\ZeroPay\Resources;

use App\Filament\ZeroPay\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use App\Services\PlanService;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class SubscriptionResource extends Resource
{
    private const DEFAULT_CURRENCY = 'USD';

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
                TextColumn::make('amount')
                    ->label('Amount')
                    ->state(fn (Subscription $record): int => static::amountFor($record))
                    ->formatStateUsing(fn (int $state, Subscription $record): string => static::formatAmount($state, static::currencyFor($record)))
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderByRaw(static::amountCaseExpression() . " {$direction}")),
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
            ->filters([
                Filter::make('amount')
                    ->label('Amount')
                    ->form([
                        Select::make('value')
                            ->label('Amount')
                            ->options(static::amountFilterOptions()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $selection = $data['value'] ?? null;

                        if (! is_string($selection) || ! str_contains($selection, ':')) {
                            return $query;
                        }

                        [$plan, $billingInterval] = explode(':', $selection, 2);

                        return $query
                            ->where('plan', $plan)
                            ->where('billing_interval', $billingInterval);
                    }),
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
            ->with(['organization.settings'])
            ->where('organization_id', $organizationId);
    }

    private static function amountFor(Subscription $subscription): int
    {
        return app(PlanService::class)->amountFor($subscription->plan, $subscription->billing_interval);
    }

    private static function formatAmount(int|float $amount, string $currency): string
    {
        return Number::currency((float) $amount, in: $currency, locale: 'en_US');
    }

    private static function currencyFor(Subscription $subscription): string
    {
        return strtoupper((string) (
            data_get($subscription, 'organization.settings.currency')
            ?? data_get($subscription, 'organization.currency')
            ?? self::DEFAULT_CURRENCY
        ));
    }

    private static function amountFilterOptions(): array
    {
        $planService = app(PlanService::class);
        $currency = static::currentUserCurrency();
        $options = [];

        foreach (PlanService::PLANS as $plan) {
            foreach (['monthly', 'annual'] as $billingInterval) {
                $options["{$plan}:{$billingInterval}"] = sprintf(
                    '%s (%s · %s)',
                    static::formatAmount($planService->amountFor($plan, $billingInterval), $currency),
                    $planService->label($plan),
                    ucfirst($billingInterval)
                );
            }
        }

        return $options;
    }

    private static function currentUserCurrency(): string
    {
        return strtoupper((string) (
            auth()->user()?->organization?->settings?->currency
            ?? auth()->user()?->organization?->currency
            ?? self::DEFAULT_CURRENCY
        ));
    }

    private static function amountCaseExpression(): string
    {
        $planService = app(PlanService::class);
        $cases = [];

        foreach (PlanService::PLANS as $plan) {
            foreach (['monthly', 'annual'] as $billingInterval) {
                $cases[] = sprintf(
                    "WHEN plan = '%s' AND billing_interval = '%s' THEN %d",
                    $plan,
                    $billingInterval,
                    $planService->amountFor($plan, $billingInterval)
                );
            }
        }

        return 'CASE ' . implode(' ', $cases) . ' ELSE 0 END';
    }
}
