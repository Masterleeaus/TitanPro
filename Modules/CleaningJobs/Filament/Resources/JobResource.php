<?php

namespace Modules\CleaningJobs\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\CleaningJobs\Actions\AssignTechnicianAction;
use Modules\CleaningJobs\Actions\CancelJobAction;
use Modules\CleaningJobs\Actions\CompleteJobAction;
use Modules\CleaningJobs\Actions\CreateJobAction;
use Modules\CleaningJobs\Models\WorkOrder;

class JobResource extends Resource
{
    protected static ?string $model = WorkOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Jobs';
    protected static ?string $navigationGroup = 'Cleaning Jobs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Job Details')->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(3),
                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])->default('open'),
                Forms\Components\Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'])
                    ->default('medium'),
                Forms\Components\DateTimePicker::make('scheduled_for'),
                Forms\Components\DateTimePicker::make('due_by'),
                Forms\Components\TextInput::make('location')->maxLength(255),
                Forms\Components\TextInput::make('budget_amount')->numeric()->prefix('$'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'open',
                        'info' => 'scheduled',
                        'warning' => ['in_progress', 'on_hold'],
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors(['gray' => 'low', 'info' => 'medium', 'warning' => 'high', 'danger' => 'urgent']),
                Tables\Columns\TextColumn::make('scheduled_for')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('due_by')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('technician.name')->label('Technician'),
                Tables\Columns\TextColumn::make('client.name')->label('Client'),
                Tables\Columns\TextColumn::make('location')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open', 'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress', 'completed' => 'Completed',
                        'cancelled' => 'Cancelled', 'on_hold' => 'On Hold',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (WorkOrder $record) => app(CompleteJobAction::class)->execute($record)),
                Tables\Actions\Action::make('cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (WorkOrder $record) => app(CancelJobAction::class)->execute($record, 'Cancelled via admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulkAssign')
                    ->label('Assign Technician')
                    ->icon('heroicon-o-user')
                    ->form([
                        Forms\Components\Select::make('technician_id')
                            ->label('Technician')
                            ->options(fn () => \App\Models\User::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (\Illuminate\Support\Collection $records, array $data) {
                        $action = app(AssignTechnicianAction::class);
                        $records->each(fn ($record) => $action->execute($record, (int) $data['technician_id']));
                    }),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\CleaningJobs\Filament\Resources\JobResource\Pages\ListJobs::route('/'),
            'create' => \Modules\CleaningJobs\Filament\Resources\JobResource\Pages\CreateJob::route('/create'),
            'edit' => \Modules\CleaningJobs\Filament\Resources\JobResource\Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
