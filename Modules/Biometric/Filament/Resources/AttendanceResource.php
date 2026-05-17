<?php

namespace Modules\Biometric\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Biometric\Entities\BiometricAttendance;
use Modules\Biometric\Filament\Resources\AttendanceResource\Pages\ListAttendances;

class AttendanceResource extends Resource
{
    protected static ?string $model = BiometricAttendance::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'Biometric';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Attendances';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('device_name')->label('Device')->toggleable(),
                Tables\Columns\TextColumn::make('timestamp')->dateTime()->sortable(),
                Tables\Columns\IconColumn::make('status1')->boolean()->label('Clock Out'),
            ])
            ->defaultSort('timestamp', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
        ];
    }
}

