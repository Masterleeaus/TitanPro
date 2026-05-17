<?php

namespace App\Filament\GroundZero\Resources;

use App\Filament\GroundZero\Resources\WorkJobResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\TitanCommand\Models\Work\WorkJob;

class WorkJobResource extends Resource
{
    protected static ?string $model = WorkJob::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Workforce';

    protected static ?string $navigationLabel = 'Work Jobs';

    protected static ?string $modelLabel = 'Work Job';

    protected static ?string $pluralModelLabel = 'Work Jobs';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job Details')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),
                    TextInput::make('job_ref')
                        ->label('Job Reference')
                        ->maxLength(80),
                    Select::make('status')
                        ->options([
                            'open'                 => 'Open',
                            'assigned'             => 'Assigned',
                            'in_progress'          => 'In Progress',
                            'checklist_complete'   => 'Checklist Complete',
                            'inspection'           => 'Inspection',
                            'closed'               => 'Closed',
                            'cancelled'            => 'Cancelled',
                        ])
                        ->default('open')
                        ->required(),
                    Select::make('priority')
                        ->options([
                            'low'    => 'Low',
                            'normal' => 'Normal',
                            'high'   => 'High',
                            'urgent' => 'Urgent',
                        ])
                        ->default('normal')
                        ->required(),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                    DateTimePicker::make('scheduled_start')
                        ->label('Scheduled Start'),
                    DateTimePicker::make('scheduled_end')
                        ->label('Scheduled End'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job_ref')
                    ->label('Ref')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open'               => 'info',
                        'assigned'           => 'warning',
                        'in_progress'        => 'warning',
                        'checklist_complete' => 'success',
                        'inspection'         => 'primary',
                        'closed'             => 'success',
                        'cancelled'          => 'danger',
                        default              => 'gray',
                    }),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high'   => 'warning',
                        'normal' => 'info',
                        'low'    => 'gray',
                        default  => 'gray',
                    }),
                TextColumn::make('scheduled_start')
                    ->label('Start')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open'               => 'Open',
                        'assigned'           => 'Assigned',
                        'in_progress'        => 'In Progress',
                        'checklist_complete' => 'Checklist Complete',
                        'inspection'         => 'Inspection',
                        'closed'             => 'Closed',
                        'cancelled'          => 'Cancelled',
                    ]),
                SelectFilter::make('priority')
                    ->options([
                        'low'    => 'Low',
                        'normal' => 'Normal',
                        'high'   => 'High',
                        'urgent' => 'Urgent',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $orgId = auth()->user()?->organization_id;
        if ($orgId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        return parent::getEloquentQuery()->where('company_id', $orgId);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWorkJobs::route('/'),
            'create' => Pages\CreateWorkJob::route('/create'),
            'edit'   => Pages\EditWorkJob::route('/{record}/edit'),
        ];
    }
}
