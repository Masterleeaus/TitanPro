<?php

namespace App\Filament\TitanSolo\Resources;

use App\Filament\TitanSolo\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Invoices';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        $organizationId = auth()->user()?->organization_id;

        return $schema->components([
            Section::make('Invoice')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'last_name', fn (Builder $query) => $query->where('organization_id', $organizationId))
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('job_id')
                        ->label('Job')
                        ->relationship('job', 'title', fn (Builder $query) => $query->where('organization_id', $organizationId))
                        ->searchable()
                        ->preload(),
                    TextInput::make('invoice_number')->label('Invoice #')->maxLength(80),
                    Select::make('status')->options(Invoice::statuses())->default(Invoice::STATUS_DRAFT)->required(),
                    TextInput::make('total')->label('Total')->numeric()->required(),
                    TextInput::make('balance_due')->label('Balance Due')->numeric()->required(),
                    DatePicker::make('due_at')->label('Due Date'),
                    Textarea::make('notes')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Invoice #')->searchable()->sortable(),
                TextColumn::make('customer.last_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, Invoice $record) => $record->customer?->full_name)
                    ->searchable(['customers.first_name', 'customers.last_name']),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('total')->money('usd')->sortable(),
                TextColumn::make('balance_due')->money('usd')->sortable(),
                TextColumn::make('due_at')->date()->sortable(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record): bool => $record->status !== Invoice::STATUS_PAID)
                    ->action(function (Invoice $record): void {
                        $record->update([
                            'status' => Invoice::STATUS_PAID,
                            'amount_paid' => $record->total,
                            'balance_due' => 0,
                            'paid_at' => now(),
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('organization_id', $organizationId)
            ->with(['customer', 'job']);
    }
}
