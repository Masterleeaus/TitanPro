<?php

namespace Modules\Accountings\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Accountings\Entities\BankTransaction;
use Modules\Accountings\Filament\Resources\PaymentSessionResource\Pages;

class PaymentSessionResource extends Resource
{
    protected static ?string $model = BankTransaction::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Payment Sessions';

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('reference')->searchable(),
            Tables\Columns\TextColumn::make('amount')->money('aud'),
            Tables\Columns\TextColumn::make('txn_date')->date(),
            Tables\Columns\IconColumn::make('is_matched')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentSessions::route('/'),
        ];
    }
}
