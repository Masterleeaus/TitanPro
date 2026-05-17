<?php

namespace Modules\ZeroPayModule\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;
use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPayRecentSessionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Sessions';

    protected static ?int $sort = 4;

    protected int $tableRecordsPerPage = 10;

    public function table(Table $table): Table
    {
        return $table
            ->query(ZeroPaySession::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('session_token')
                    ->label('Token')
                    ->limit(20)
                    ->searchable(),

                Tables\Columns\TextColumn::make('gateway')
                    ->label('Gateway')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'payid' => 'PayID',
                        'bank_transfer' => 'Bank Transfer',
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'cash' => 'Cash',
                        'cryptomus' => 'Cryptomus',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('AUD'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'processing' => 'primary',
                        'opened' => 'info',
                        'pending' => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (ZeroPaySession $record): string => ZeroPaySessionResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
