<?php

namespace Modules\ZeroPayModule\Filament\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Modules\ZeroPayModule\Actions\CompleteZeroPaySessionAction;
use Modules\ZeroPayModule\Actions\ExpireZeroPaySessionAction;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource\Pages;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPaySessionResource extends Resource
{
    protected static ?string $model = ZeroPaySession::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $navigationLabel = 'Payment Sessions';

    protected static ?string $modelLabel = 'Payment Session';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('session_token')
                ->label('Session Token')
                ->disabled()
                ->dehydrated(false),
            Select::make('gateway')
                ->label('Gateway')
                ->options([
                    'payid' => 'PayID',
                    'bank_transfer' => 'Bank Transfer',
                    'cash' => 'Cash',
                    'stripe' => 'Stripe',
                    'paypal' => 'PayPal',
                    'cryptomus' => 'Cryptomus',
                ])
                ->required(),
            TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->prefix('AUD'),
            TextInput::make('currency')
                ->label('Currency')
                ->default('AUD')
                ->maxLength(3),
            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'opened' => 'Opened',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('session_token')
                    ->label('Token')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('qrCode.merchant_name')
                    ->label('Merchant')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('AUD'),
                TextColumn::make('gateway')
                    ->label('Gateway')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'completed' => 'success',
                        'failed', 'expired' => 'danger',
                        'processing' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'opened' => 'Opened',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'expired' => 'Expired',
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
                    ->label('Created Date')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn ($q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn ($q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Action::make('viewQr')
                    ->label('View QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Session QR Code')
                    ->modalContent(fn (ZeroPaySession $record) => new HtmlString(
                        static::renderQrModalContent($record)
                    ))
                    ->modalSubmitAction(false)
                    ->visible(fn (ZeroPaySession $record) => $record->isActive()),
                Action::make('forceComplete')
                    ->label('Force Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Force Complete Session')
                    ->modalDescription('Are you sure you want to mark this session as completed?')
                    ->action(fn (ZeroPaySession $record) => app(CompleteZeroPaySessionAction::class)->execute($record))
                    ->visible(fn () => auth()->user()?->can('zeropay.approve') ?? false),
                Action::make('forceExpire')
                    ->label('Force Expire')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Force Expire Session')
                    ->modalDescription('Are you sure you want to expire this session?')
                    ->action(fn (ZeroPaySession $record) => app(ExpireZeroPaySessionAction::class)->execute($record))
                    ->visible(fn () => auth()->user()?->can('zeropay.approve') ?? false),
                Action::make('viewTransactions')
                    ->label('View Transactions')
                    ->icon('heroicon-o-banknotes')
                    ->url(fn (ZeroPaySession $record) => ZeroPayTransactionResource::getUrl('index').'?tableFilters[session_id][value]='.$record->id)
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function renderQrModalContent(ZeroPaySession $record): string
    {
        $qrCode = $record->qrCode;

        if (! $qrCode) {
            return '<p class="text-center text-gray-500 py-4">No QR code available for this session.</p>';
        }

        if (! $qrCode->qr_image_path) {
            return '<p class="text-center text-gray-500 py-4">QR image has not been generated yet.</p>';
        }

        $imageUrl = Storage::url($qrCode->qr_image_path);

        return sprintf(
            '<div class="flex flex-col items-center gap-4 p-4">'
            .'<img src="%s" alt="QR Code" class="w-64 h-64 object-contain border rounded" />'
            .'<p class="text-sm font-medium text-gray-700">%s</p>'
            .'<p class="text-xs text-gray-400 font-mono">%s</p>'
            .'</div>',
            e($imageUrl),
            e($qrCode->merchant_name ?? ''),
            e($record->session_token)
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZeroPaySessions::route('/'),
            'create' => Pages\CreateZeroPaySession::route('/create'),
            'edit' => Pages\EditZeroPaySession::route('/{record}/edit'),
        ];
    }
}
