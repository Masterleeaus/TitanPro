<?php

namespace Modules\BookingModule\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Filament\Resources\ScheduleResource\Pages;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Booking & Dispatch';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date'),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
            Forms\Components\TextInput::make('location'),
            Forms\Components\Select::make('status')->options(['Approved'=>'Approved','Pending'=>'Pending','Cancelled'=>'Cancelled']),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('date')->date()->sortable(),
            Tables\Columns\TextColumn::make('start_time'),
            Tables\Columns\TextColumn::make('assigned_to'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])->actions([Tables\Actions\EditAction::make()]);
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListSchedules::route('/'), 'create' => Pages\CreateSchedule::route('/create'), 'edit' => Pages\EditSchedule::route('/{record}/edit')];
    }
}
