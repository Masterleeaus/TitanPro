<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobTypeResource\Pages;
use App\Models\JobChecklistItem;
use App\Models\JobType;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobTypeResource extends Resource
{
    protected static ?string $model = JobType::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';
    protected static ?string $navigationLabel = 'Services';
    protected static ?string $modelLabel = 'Service';
    protected static ?string $pluralModelLabel = 'Services';
    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Service Details')->columnSpanFull()->columns(1)->schema([
                TextInput::make('name')->label('Service Name')->required()->maxLength(255),
                Select::make('service_category')->label('Category')->options([
                    'residential' => 'Residential', 'commercial' => 'Commercial', 'deep_clean' => 'Deep Clean', 'move_out' => 'Move-out / End-of-lease', 'airbnb' => 'Airbnb / Turnover', 'specialty' => 'Specialty',
                ])->default('residential'),
                TextInput::make('default_price')->label('Default Price')->numeric()->prefix('$')->minValue(0)->step(0.01),
                TextInput::make('default_duration_minutes')->label('Default Duration (minutes)')->numeric()->minValue(0),
                TextInput::make('recommended_team_size')->label('Recommended Team Size')->numeric()->minValue(1)->default(1),
                ColorPicker::make('color')->required()->default('#6366f1')->rules(['regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/']),
                Toggle::make('allows_recurring')->label('Can Be Recurring')->default(true),
                Toggle::make('requires_quality_check')->label('Quality Check by Default')->default(false),
                Textarea::make('required_equipment')->label('Required Equipment')->rows(3)->columnSpanFull(),
                Select::make('task_library_item_ids')
                    ->label('Task Library Bindings')
                    ->multiple()
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->options(fn (): array => JobChecklistItem::query()
                        ->where('organization_id', auth()->user()?->organization_id)
                        ->whereNull('job_id')
                        ->orderBy('sort_order')
                        ->orderBy('label')
                        ->pluck('label', 'id')
                        ->all())
                    ->default(fn (?JobType $record): array => $record?->checklistItems()
                        ->whereNotNull('task_library_item_id')
                        ->pluck('task_library_item_id')
                        ->all() ?? []),
                Textarea::make('description')->label('Service Scope')->rows(3)->columnSpanFull(),
                Toggle::make('is_active')->label('Active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')->copyable(),
                TextColumn::make('name')->label('Service')->searchable()->sortable(),
                TextColumn::make('service_category')->label('Category')->badge()->sortable(),
                TextColumn::make('default_price')->label('Default Price')->money('USD')->sortable(),
                TextColumn::make('default_duration_minutes')->label('Mins')->sortable(),
                TextColumn::make('recommended_team_size')->label('Team')->sortable(),
                IconColumn::make('allows_recurring')->label('Recurring')->boolean(),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('jobs_count')->label('Jobs')->counts('jobs'),
            ])
            ->filters([
                SelectFilter::make('service_category')->label('Category')->options(['residential' => 'Residential', 'commercial' => 'Commercial', 'deep_clean' => 'Deep Clean', 'move_out' => 'Move-out / End-of-lease', 'airbnb' => 'Airbnb / Turnover', 'specialty' => 'Specialty']),
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListJobTypes::route('/'), 'create' => Pages\CreateJobType::route('/create'), 'edit' => Pages\EditJobType::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('organization_id', auth()->user()?->organization_id);
    }

    public static function syncChecklistBindings(JobType $jobType, array $taskIds): void
    {
        $taskIds = array_values(array_unique(array_map('intval', $taskIds)));

        if ($taskIds === []) {
            $jobType->checklistItems()
                ->whereNotNull('task_library_item_id')
                ->delete();
        } else {
            $jobType->checklistItems()
                ->whereNotNull('task_library_item_id')
                ->whereNotIn('task_library_item_id', $taskIds)
                ->delete();
        }

        $tasks = JobChecklistItem::query()
            ->where('organization_id', $jobType->organization_id)
            ->whereNull('job_id')
            ->whereIn('id', $taskIds)
            ->get()
            ->keyBy('id');

        foreach ($taskIds as $index => $taskId) {
            $task = $tasks->get($taskId);

            if (! $task) {
                continue;
            }

            $jobType->checklistItems()->updateOrCreate(
                ['task_library_item_id' => $task->id],
                [
                    'label' => $task->label,
                    'instructions' => $task->instructions,
                    'sort_order' => $index + 1,
                    'is_required' => $task->is_required,
                    'required_override' => false,
                    'requires_photo' => $task->requires_photo,
                ],
            );
        }
    }
}
