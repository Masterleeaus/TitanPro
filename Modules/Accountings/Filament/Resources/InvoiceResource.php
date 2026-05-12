<?php

namespace Modules\Accountings\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Accountings\Filament\Resources\InvoiceResource\Pages;
use Modules\EInvoice\Actions\GenerateLateInvoiceFollowupAction;
use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Entities\Invoice;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Invoices';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('due_date')->date(),
                Tables\Columns\TextColumn::make('grand_total')->money('aud')->label('Total'),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Send')
                    ->action(fn (Invoice $record) => app(SendInvoiceAction::class)->execute($record, ['source' => 'accountings_filament'])),
                Tables\Actions\Action::make('follow_up')
                    ->label('Follow-up')
                    ->action(function (Invoice $record): array {
                        $daysOverdue = max(0, now()->diffInDays($record->due_date ?? now(), false) * -1);

                        return app(GenerateLateInvoiceFollowupAction::class)->execute($record, $daysOverdue);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
        ];
    }
}
