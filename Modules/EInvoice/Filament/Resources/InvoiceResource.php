<?php

namespace Modules\EInvoice\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\EInvoice\Actions\MarkInvoicePaidAction;
use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Actions\VoidInvoiceAction;
use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Filament\Resources\InvoiceResource\Pages;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'E-Invoices';
    protected static ?string $slug = 'einvoices';
    protected static ?int $navigationSort = 200;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')
                ->label('Client')
                ->searchable()
                ->nullable(),

            Forms\Components\Select::make('currency')
                ->label('Currency')
                ->options(['AUD' => 'AUD', 'USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP'])
                ->default('AUD')
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'draft'      => 'Draft',
                    'sent'       => 'Sent',
                    'paid'       => 'Paid',
                    'void'       => 'Void',
                    'written_off' => 'Written Off',
                ])
                ->default('draft')
                ->required(),

            Forms\Components\DatePicker::make('due_date')
                ->label('Due Date')
                ->nullable(),

            Forms\Components\Textarea::make('notes')
                ->label('Notes')
                ->nullable()
                ->columnSpanFull(),

            Forms\Components\TextInput::make('subtotal')
                ->label('Subtotal')
                ->numeric()
                ->default(0),

            Forms\Components\TextInput::make('tax_total')
                ->label('Tax Total')
                ->numeric()
                ->default(0),

            Forms\Components\TextInput::make('grand_total')
                ->label('Grand Total')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('client_id')
                    ->label('Client ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'       => 'success',
                        'sent'       => 'info',
                        'draft'      => 'warning',
                        'void'       => 'danger',
                        'written_off' => 'gray',
                        default      => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total')
                    ->money(fn ($record) => strtolower($record->currency ?? 'aud'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'      => 'Draft',
                        'sent'       => 'Sent',
                        'paid'       => 'Paid',
                        'void'       => 'Void',
                        'written_off' => 'Written Off',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('send')
                    ->label('Send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record): bool => $record->status === 'draft')
                    ->action(function (Invoice $record): void {
                        app(SendInvoiceAction::class)->execute($record, [
                            'source'   => 'filament',
                            'actor_id' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record): bool => in_array($record->status, ['sent', 'draft']))
                    ->action(function (Invoice $record): void {
                        app(MarkInvoicePaidAction::class)->execute($record, [
                            'actor_id' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\Action::make('void')
                    ->label('Void')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record): bool => in_array($record->status, ['draft', 'sent']))
                    ->action(function (Invoice $record): void {
                        app(VoidInvoiceAction::class)->execute($record, [
                            'actor_id' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit'   => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) (
            $user?->can('einvoice.view')
            || $user?->can('einvoice.create')
        );
    }
}
