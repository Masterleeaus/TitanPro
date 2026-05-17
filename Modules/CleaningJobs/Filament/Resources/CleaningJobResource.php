<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources;

use Filament\Actions;
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
use Modules\CleaningJobs\Filament\Resources\CleaningJobResource\Pages;
use Modules\CleaningJobs\Models\WorkOrder;

class CleaningJobResource extends Resource
{
    protected static ?string $model = WorkOrder::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';
    protected static string|\UnitEnum|null $navigationGroup = 'Cleaning Jobs';
    protected static ?string $navigationLabel = 'Jobs';
    protected static ?string $modelLabel = 'Cleaning Job';
    protected static ?string $pluralModelLabel = 'Cleaning Jobs';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job Details')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    TextInput::make('type')
                        ->maxLength(100),
                    Select::make('status')
                        ->options([
                            'pending'   => 'Pending',
                            'approved'  => 'Approved',
                            'rejected'  => 'Rejected',
                            'on_hold'   => 'On Hold',
                            'cancelled' => 'Cancelled',
                            'completed' => 'Completed',
                            'done'      => 'Done',
                        ])
                        ->default('pending')
                        ->required(),
                    Select::make('priority')
                        ->options([
                            'low'    => 'Low',
                            'medium' => 'Medium',
                            'high'   => 'High',
                            'urgent' => 'Urgent',
                        ])
                        ->default('medium'),
                    DateTimePicker::make('scheduled_for')
                        ->label('Scheduled For'),
                    DateTimePicker::make('due_by')
                        ->label('Due By'),
                    TextInput::make('budget_amount')
                        ->numeric()
                        ->prefix('$')
                        ->label('Budget'),
                    TextInput::make('actual_cost')
                        ->numeric()
                        ->prefix('$')
                        ->label('Actual Cost'),
                    TextInput::make('estimated_hours')
                        ->numeric()
                        ->label('Estimated Hours'),
                    TextInput::make('actual_hours')
                        ->numeric()
                        ->label('Actual Hours'),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning'   => 'pending',
                        'success'   => ['approved', 'completed', 'done'],
                        'danger'    => ['cancelled', 'rejected'],
                        'secondary' => 'on_hold',
                    ]),
                BadgeColumn::make('priority')
                    ->colors([
                        'secondary' => 'low',
                        'warning'   => 'medium',
                        'danger'    => ['high', 'urgent'],
                    ]),
                TextColumn::make('scheduled_for')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('due_by')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('budget_amount')
                    ->money('USD')
                    ->label('Budget'),
                TextColumn::make('actual_cost')
                    ->money('USD')
                    ->label('Actual Cost'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCleaningJobs::route('/'),
            'create' => Pages\CreateCleaningJob::route('/create'),
            'edit'   => Pages\EditCleaningJob::route('/{record}/edit'),
        ];
    }
}
