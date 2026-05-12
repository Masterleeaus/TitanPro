<?php

namespace Modules\CleaningJobs\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Modules\CleaningJobs\Models\WorkOrder;

class LiveJobBoardWidget extends TableWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Live Job Board';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                WorkOrder::query()
                    ->whereIn('status', ['open', 'scheduled', 'in_progress', 'on_hold'])
                    ->orderByRaw("FIELD(status, 'in_progress', 'scheduled', 'on_hold', 'open')")
                    ->orderBy('due_by')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->limit(35)->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'open',
                        'info' => 'scheduled',
                        'warning' => ['in_progress', 'on_hold'],
                    ]),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors(['gray' => 'low', 'info' => 'medium', 'warning' => 'high', 'danger' => 'urgent']),
                Tables\Columns\TextColumn::make('scheduled_for')->dateTime(),
                Tables\Columns\TextColumn::make('due_by')->dateTime(),
                Tables\Columns\TextColumn::make('technician.name')->label('Technician')->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('location')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([10, 25, 50]);
    }
}
