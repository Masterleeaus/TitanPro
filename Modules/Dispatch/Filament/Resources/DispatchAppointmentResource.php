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
use Modules\Dispatch\Filament\Resources\DispatchAppointmentResource\Pages;
use Modules\Dispatch\Models\DispatchAppointment;

class DispatchAppointmentResource extends Resource
{
    protected static ?string $model = DispatchAppointment::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Appointments';
    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Appointment')
                ->columns(2)
                ->schema([
                    TextInput::make('work_order_id')->numeric()->required(),
                    TextInput::make('technician_id')->numeric(),
                    TextInput::make('shift_id')->numeric(),
                    TextInput::make('customer_location_id')->numeric(),
                    DateTimePicker::make('starts_at')->required(),
                    DateTimePicker::make('ends_at'),
                    Select::make('status')->options(['scheduled' => 'Scheduled', 'dispatched' => 'Dispatched', 'en_route' => 'En Route', 'arrived' => 'Arrived', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'])->default('scheduled'),
                    TextInput::make('location')->maxLength(255)->columnSpanFull(),
                    Textarea::make('notes')->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('workOrder.title')->label('Work Order')->searchable(),
            TextColumn::make('technician.name')->label('Technician')->searchable(),
            TextColumn::make('starts_at')->dateTime()->sortable(),
            TextColumn::make('ends_at')->dateTime()->sortable(),
            BadgeColumn::make('status'),
            TextColumn::make('location')->searchable(),
        ])->defaultSort('starts_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDispatchAppointments::route('/'),
            'create' => Pages\CreateDispatchAppointment::route('/create'),
            'edit' => Pages\EditDispatchAppointment::route('/{record}/edit'),
        ];
    }
}
