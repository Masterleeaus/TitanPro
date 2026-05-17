<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Budgeting\Filament\Resources\ReimbursementBatchResource\Pages;
use Modules\Budgeting\Models\ReimbursementBatch;

class ReimbursementBatchResource extends Resource
{
    protected static ?string $model = ReimbursementBatch::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|\UnitEnum|null $navigationGroup = 'Budgeting';
    protected static ?string $navigationLabel = 'Reimbursement Batches';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->searchable()->sortable(),
            BadgeColumn::make('status')
                ->colors([
                    'secondary' => 'draft',
                    'warning' => 'submitted',
                    'primary' => 'approved',
                    'success' => 'paid',
                ]),
            TextColumn::make('total_amount')->money('AUD')->sortable(),
            TextColumn::make('paid_at')->dateTime()->sortable()->toggleable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReimbursementBatches::route('/'),
            'create' => Pages\CreateReimbursementBatch::route('/create'),
            'edit' => Pages\EditReimbursementBatch::route('/{record}/edit'),
        ];
    }
}
