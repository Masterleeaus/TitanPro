<?php

namespace Modules\Biometric\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Dashboard;
use Modules\Biometric\Actions\RecordAttendanceAction;
use Modules\Biometric\Filament\Widgets\OvertimeAlertWidget;
use Modules\Biometric\Filament\Widgets\ShiftCoverageWidget;

class AttendanceDashboardPage extends Dashboard
{
    protected static ?string $slug = 'biometric/attendance-dashboard';
    protected static ?string $navigationLabel = 'Attendance Dashboard';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static string|\UnitEnum|null $navigationGroup = 'Biometric';
    protected static ?int $navigationSort = 0;

    protected function getWidgets(): array
    {
        return [
            ShiftCoverageWidget::class,
            OvertimeAlertWidget::class,
        ];
    }

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
                    TextInput::make('device_serial_number')->default('dashboard'),
                ])
                ->action(fn (array $data) => app(RecordAttendanceAction::class)->execute($data)),
        ];
    }
}
