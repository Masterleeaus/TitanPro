<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\DispatchChecklistResource\Pages;
use Modules\Dispatch\Models\DispatchChecklist;

class DispatchChecklistResource extends Resource
{
    protected static ?string $model = DispatchChecklist::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Checklists';
    protected static ?int $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Checklist')->columns(2)->schema([
                TextInput::make('work_order_id')->numeric()->required(),
                TextInput::make('name')->required()->maxLength(255),
                Select::make('status')->options(['open' => 'Open', 'completed' => 'Completed', 'cancelled' => 'Cancelled'])->default('open'),
                TextInput::make('completed_by')->numeric(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('workOrder.reference')->label('Work Order')->searchable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('status')->badge(),
            TextColumn::make('items_count')->counts('items')->label('Items'),
            TextColumn::make('updated_at')->dateTime()->sortable(),
        ])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchChecklists::route('/'),
            'create' => Pages\CreateDispatchChecklist::route('/create'),
            'edit' => Pages\EditDispatchChecklist::route('/{record}/edit'),
        ];
    }
}
