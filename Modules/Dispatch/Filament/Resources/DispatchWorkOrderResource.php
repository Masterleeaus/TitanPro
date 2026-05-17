<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource\Pages;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchWorkOrderResource extends Resource
{
    protected static ?string $model = DispatchWorkOrder::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Work Orders';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Work Order')
                ->columns(2)
                ->schema([
                    TextInput::make('title')->required()->maxLength(255),
                    TextInput::make('reference')->maxLength(255),
                    Select::make('status')->options(['draft' => 'Draft', 'ready_for_dispatch' => 'Ready', 'scheduled' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'])->default('draft'),
                    Select::make('priority')->options(['low' => 'Low', 'normal' => 'Normal', 'high' => 'High', 'urgent' => 'Urgent'])->default('normal'),
                    TextInput::make('customer_id')->numeric(),
                    TextInput::make('customer_location_id')->numeric(),
                    TextInput::make('technician_id')->numeric(),
                    TextInput::make('estimated_hours')->numeric()->step('0.25'),
                    DateTimePicker::make('scheduled_for'),
                    TextInput::make('location')->maxLength(255)->columnSpanFull(),
                    Textarea::make('description')->columnSpanFull(),
                    Textarea::make('notes')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->searchable()->sortable(),
            TextColumn::make('title')->searchable()->sortable(),
            BadgeColumn::make('status'),
            BadgeColumn::make('priority'),
            TextColumn::make('technician.name')->label('Technician')->searchable(),
            TextColumn::make('scheduled_for')->dateTime()->sortable(),
            TextColumn::make('updated_at')->dateTime()->sortable(),
        ])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchWorkOrders::route('/'),
            'create' => Pages\CreateDispatchWorkOrder::route('/create'),
            'edit' => Pages\EditDispatchWorkOrder::route('/{record}/edit'),
        ];
    }
}
