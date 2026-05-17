<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\BankTransferMatchingService;

class ZeroPayBankDepositResource extends Resource
{
    protected static ?string $model = ZeroPayBankDeposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $navigationLabel = 'Bank Deposits';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('amount')->numeric()->required(),
            TextInput::make('currency')->default('AUD')->maxLength(3),
            TextInput::make('depositor_name')->label('Depositor Name'),
            TextInput::make('reference')->label('Reference'),
            TextInput::make('description')->label('Description'),
            Select::make('status')->options([
                'pending_review' => 'Pending Review',
                'matched' => 'Matched',
                'unmatched' => 'Unmatched',
                'ignored' => 'Ignored',
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('depositor_name')
                    ->label('Depositor Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('AUD')
                    ->sortable(),
                TextColumn::make('currency')
                    ->label('Currency'),
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable(),
                TextColumn::make('deposited_at')
                    ->label('Deposited At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending_review',
                        'success' => 'matched',
                        'danger' => 'unmatched',
                        'gray' => 'ignored',
                    ]),
                TextColumn::make('transaction_id')
                    ->label('Matched Transaction')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('matchManually')
                    ->label('Match Manually')
                    ->icon('heroicon-o-link')
                    ->color('success')
                    ->visible(fn (ZeroPayBankDeposit $record): bool => $record->status === 'pending_review')
                    ->form(fn (ZeroPayBankDeposit $record): array => [
                        Select::make('session_id')
                            ->label('Session')
                            ->options(
                                ZeroPaySession::query()
                                    ->where('company_id', $record->company_id)
                                    ->whereIn('status', ['pending', 'opened', 'processing'])
                                    ->where('gateway', 'bank_transfer')
                                    ->get()
                                    ->mapWithKeys(fn (ZeroPaySession $s): array => [
                                        $s->id => "{$s->session_token} — {$s->amount} {$s->currency}",
                                    ])
                                    ->all()
                            )
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (ZeroPayBankDeposit $record, array $data): void {
                        app(BankTransferMatchingService::class)->confirmMatch($record->id, (int) $data['session_id']);

                        Notification::make()
                            ->title('Deposit matched successfully')
                            ->success()
                            ->send();
                    })
                    ->authorize(fn (): bool => auth()->user()?->can('zeropay.approve') ?? false),

                Action::make('markUnmatched')
                    ->label('Mark Unmatched')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ZeroPayBankDeposit $record): bool => $record->status === 'pending_review')
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (ZeroPayBankDeposit $record, array $data): void {
                        app(BankTransferMatchingService::class)->rejectMatch($record->id, $data['reason']);

                        Notification::make()
                            ->title('Deposit marked as unmatched')
                            ->warning()
                            ->send();
                    })
                    ->authorize(fn (): bool => auth()->user()?->can('zeropay.approve') ?? false),

                Action::make('viewRawData')
                    ->label('Raw Data')
                    ->icon('heroicon-o-code-bracket')
                    ->color('gray')
                    ->form(fn (ZeroPayBankDeposit $record): array => [
                        Textarea::make('raw_data')
                            ->label('Raw Data (JSON)')
                            ->default(
                                json_encode($record->raw_data ?? $record->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                            )
                            ->disabled()
                            ->rows(15)
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitAction(false),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZeroPayBankDeposits::route('/'),
        ];
    }
}
