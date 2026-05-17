<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Actions;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Modules\CleaningJobs\Actions\AssignTechnicianAction as AssignAction;

class AssignTechnicianAction extends BulkAction
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'assign_technician')
            ->label('Assign Technician')
            ->icon('heroicon-o-user')
            ->form([
                TextInput::make('technician_id')
                    ->label('Technician ID')
                    ->numeric()
                    ->required(),
            ])
            ->action(function (Collection $records, array $data): void {
                $action = new AssignAction();
                $records->each(fn ($record) => $action->handle($record, (int) $data['technician_id']));
            });
    }
}
