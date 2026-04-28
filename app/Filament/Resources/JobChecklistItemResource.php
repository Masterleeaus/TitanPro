<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobChecklistItemResource\Pages;
use App\Models\JobChecklistItem;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobChecklistItemResource extends Resource
{
    protected static ?string $model = JobChecklistItem::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';
    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';
    protected static ?string $navigationLabel = 'Task Library';
    protected static ?string $modelLabel = 'Task';
    protected static ?string $pluralModelLabel = 'Task Library';
    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Task Details')->columnSpanFull()->columns(1)->schema([
                Select::make('job_id')->label('Job')->relationship('job', 'title', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->searchable()->preload()->helperText('Leave blank when this is a reusable library task.'),
                Select::make('category')->options(['general' => 'General', 'kitchen' => 'Kitchen', 'bathroom' => 'Bathroom', 'bedroom' => 'Bedroom', 'floors' => 'Floors', 'windows' => 'Windows', 'laundry' => 'Laundry', 'exterior' => 'Exterior', 'quality' => 'Quality Check'])->default('general'),
                TextInput::make('label')->label('Task Name')->required()->maxLength(200)->columnSpanFull(),
                Textarea::make('instructions')->label('Cleaner Instructions')->rows(3)->columnSpanFull(),
                TextInput::make('estimated_minutes')->label('Estimated Minutes')->numeric()->minValue(0),
                TextInput::make('sort_order')->label('Display Order')->numeric()->default(0),
                Toggle::make('is_required')->label('Required')->default(true),
                Toggle::make('requires_photo')->label('Photo Required')->default(false),
                DateTimePicker::make('completed_at')->label('Completed At')->helperText('Only used for tasks attached to a job.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('Task')->searchable()->sortable(),
                TextColumn::make('category')->badge()->sortable(),
                TextColumn::make('job.title')->label('Job')->placeholder('Library task')->limit(30),
                TextColumn::make('estimated_minutes')->label('Mins')->sortable(),
                IconColumn::make('is_required')->label('Required')->boolean(),
                IconColumn::make('requires_photo')->label('Photo')->boolean(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')->options(['general' => 'General', 'kitchen' => 'Kitchen', 'bathroom' => 'Bathroom', 'bedroom' => 'Bedroom', 'floors' => 'Floors', 'windows' => 'Windows', 'laundry' => 'Laundry', 'exterior' => 'Exterior', 'quality' => 'Quality Check']),
            ])
            ->recordActions([Actions\EditAction::make()])
            ->toolbarActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListJobChecklistItems::route('/'), 'create' => Pages\CreateJobChecklistItem::route('/create'), 'edit' => Pages\EditJobChecklistItem::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(function (Builder $query) {
            $query->where('organization_id', auth()->user()?->organization_id)
                ->orWhereHas('job', fn (Builder $jobQuery) => $jobQuery->where('organization_id', auth()->user()?->organization_id));
        });
    }
}
