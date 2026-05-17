<?php

namespace Modules\Biometric\Filament\Resources\AttendanceResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Modules\Biometric\Actions\RecordAttendanceAction;
use Modules\Biometric\Filament\Resources\AttendanceResource;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recordAttendance')
                ->label('Record attendance')
                ->form([
                    TextInput::make('company_id')->required()->numeric(),
                    TextInput::make('employee_id')->required(),
                    TextInput::make('user_id')->numeric(),
                    TextInput::make('device_name')->default('Manual entry'),
                    TextInput::make('device_serial_number')->default('manual'),
                ])
                ->action(fn (array $data) => app(RecordAttendanceAction::class)->execute($data)),
        ];
    }
}

