<?php

namespace Modules\CleaningJobs\Filament\Actions;

use Filament\Tables\Actions\Action;
use Modules\CleaningJobs\Actions\AssignTechnicianAction as AssignAction;
use Modules\CleaningJobs\Models\WorkOrder;

class AssignTechnicianAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'assignTechnician';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Assign Technician')
            ->icon('heroicon-o-user-plus')
            ->form([
                \Filament\Forms\Components\Select::make('technician_id')
                    ->label('Technician')
                    ->options(fn () => \App\Models\User::all()->pluck('name', 'id'))
                    ->required(),
            ])
            ->action(function (WorkOrder $record, array $data): void {
                app(AssignAction::class)->execute($record, (int) $data['technician_id']);
            });
    }
}
