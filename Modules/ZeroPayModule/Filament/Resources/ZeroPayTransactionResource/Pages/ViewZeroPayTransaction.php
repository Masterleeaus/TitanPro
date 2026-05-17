<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource\Pages;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource;

class ViewZeroPayTransaction extends ViewRecord
{
    protected static string $resource = ZeroPayTransactionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Transaction Details')
                ->columns(2)
                ->schema([
                    TextEntry::make('id')
                        ->label('ID'),

                    TextEntry::make('type')
                        ->label('Type')
                        ->badge()
                        ->color(fn (?string $state): string => match ($state) {
                            'refund' => 'warning',
                            'fee' => 'gray',
                            default => 'primary',
                        }),

                    TextEntry::make('gateway')
                        ->label('Gateway')
                        ->badge(),

                    TextEntry::make('gateway_reference')
                        ->label('Gateway Reference')
                        ->copyable(),

                    TextEntry::make('amount')
                        ->label('Amount')
                        ->money('AUD'),

                    TextEntry::make('fee')
                        ->label('Fee')
                        ->money('AUD'),

                    TextEntry::make('net_amount')
                        ->label('Net Amount')
                        ->money('AUD'),

                    TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            'refunded' => 'info',
                            default => 'gray',
                        }),

                    TextEntry::make('payer_name')
                        ->label('Payer Name'),

                    TextEntry::make('payee_name')
                        ->label('Payee Name'),

                    TextEntry::make('reference')
                        ->label('Reference')
                        ->copyable(),

                    TextEntry::make('failure_reason')
                        ->label('Failure Reason')
                        ->columnSpanFull()
                        ->hidden(fn ($record) => blank($record->failure_reason)),
                ]),

            Section::make('Session Link')
                ->schema([
                    TextEntry::make('session.session_token')
                        ->label('Session Token')
                        ->copyable()
                        ->default('—'),

                    TextEntry::make('session.status')
                        ->label('Session Status')
                        ->badge()
                        ->default('—'),

                    TextEntry::make('session_id')
                        ->label('Session ID'),
                ]),

            Section::make('Gateway Response')
                ->schema([
                    TextEntry::make('gateway_response')
                        ->label('Raw Response')
                        ->columnSpanFull()
                        ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT) : '—')
                        ->extraAttributes(['class' => 'font-mono text-xs whitespace-pre']),
                ]),

            Section::make('Timeline')
                ->columns(2)
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Created At')
                        ->dateTime(),

                    TextEntry::make('updated_at')
                        ->label('Last Updated')
                        ->dateTime(),
                ]),
        ]);
    }
}
