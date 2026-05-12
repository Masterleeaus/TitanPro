<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Widgets;

use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\CleaningJobs\Models\WorkOrder;

class LiveJobBoardWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                WorkOrder::query()
                    ->whereIn('status', ['pending', 'approved', 'in_progress'])
                    ->orderBy('due_by')
            )
            ->columns([
                TextColumn::make('title')->searchable(),
                BadgeColumn::make('status'),
                BadgeColumn::make('priority'),
                TextColumn::make('due_by')->dateTime()->label('Due By'),
                TextColumn::make('scheduled_for')->dateTime()->label('Scheduled'),
            ]);
    }
}
