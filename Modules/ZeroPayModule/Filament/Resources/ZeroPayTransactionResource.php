<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\ZeroPayModule\Exports\ZeroPayTransactionsExport;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayTransactionResource extends Resource
{
    protected static ?string $model = ZeroPayTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $navigationLabel = 'Transactions';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'refund' => 'warning',
                        'fee' => 'gray',
                        default => 'primary',
                    }),

                TextColumn::make('gateway')
                    ->label('Gateway')
                    ->badge(),

                TextColumn::make('gateway_reference')
                    ->label('Gateway Ref')
                    ->copyable()
                    ->limit(30),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('AUD')
                    ->sortable(),

                TextColumn::make('fee')
                    ->label('Fee')
                    ->money('AUD'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('payer_name')
                    ->label('Payer')
                    ->searchable(),

                TextColumn::make('payee_name')
                    ->label('Payee')
                    ->searchable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'payment' => 'Payment',
                        'refund' => 'Refund',
                        'fee' => 'Fee',
                    ]),

                SelectFilter::make('gateway')
                    ->label('Gateway')
                    ->options([
                        'payid' => 'PayID',
                        'bank_transfer' => 'Bank Transfer',
                        'cash' => 'Cash',
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'cryptomus' => 'Cryptomus',
                    ]),

                Filter::make('created_at')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn (Builder $q, string $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn (Builder $q, string $date) => $q->whereDate('created_at', '<=', $date));
                    }),

                Filter::make('amount_range')
                    ->label('Amount Range')
                    ->form([
                        TextInput::make('amount_from')->label('Min Amount')->numeric(),
                        TextInput::make('amount_to')->label('Max Amount')->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['amount_from'] ?? null, fn (Builder $q, string $amount) => $q->where('amount', '>=', $amount))
                            ->when($data['amount_to'] ?? null, fn (Builder $q, string $amount) => $q->where('amount', '<=', $amount));
                    }),
            ])
            ->headerActions([
                Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn () => auth()->user()?->can('zeropay.export') ?? false)
                    ->action(function () {
                        $export = new ZeroPayTransactionsExport(
                            ZeroPayTransaction::query()
                        );

                        $filename = 'transactions-'.now()->format('Y-m-d').'.csv';

                        return $export->download($filename);
                    }),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZeroPayTransactions::route('/'),
            'view' => Pages\ViewZeroPayTransaction::route('/{record}'),
        ];
    }
}
