<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobTypeChecklistItemResource\Pages;
use App\Models\JobChecklistItem;
use App\Models\JobTypeChecklistItem;
use Filament\Actions;
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

class JobTypeChecklistItemResource extends Resource
{
    protected static ?string $model = JobTypeChecklistItem::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';
    protected static ?string $navigationLabel = 'Service Checklists';
    protected static ?string $modelLabel = 'Service Checklist Task';
    protected static ?string $pluralModelLabel = 'Service Checklists';
    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Service Checklist Task')->columnSpanFull()->columns(1)->schema([
                Select::make('job_type_id')->label('Service')->relationship('jobType', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->searchable()->preload()->required(),
                Select::make('task_library_item_id')->label('Task Library Item')->options(fn () => JobChecklistItem::query()->where('organization_id', auth()->user()?->organization_id)->whereNull('job_id')->orderBy('label')->pluck('label', 'id'))->searchable()->preload()->helperText('Optional: link to a reusable task.'),
                TextInput::make('label')->label('Checklist Task')->required()->maxLength(200)->columnSpanFull(),
                Textarea::make('instructions')->label('Cleaner Instructions')->rows(3)->columnSpanFull(),
                TextInput::make('sort_order')->label('Display Order')->numeric()->default(0),
                Select::make('condition_type')->label('Condition')->options([
                    'always' => 'Always show', 'if_addon_selected' => 'Only if add-on selected', 'if_room_count' => 'Based on room count', 'if_bathroom_count' => 'Based on bathroom count',
                ])->default('always'),
                TextInput::make('condition_value')->label('Condition Value')->maxLength(255),
                Toggle::make('is_required')->label('Required')->default(true),
                Toggle::make('requires_photo')->label('Photo Required')->default(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobType.name')->label('Service')->searchable()->sortable(),
                TextColumn::make('label')->label('Checklist Task')->searchable()->sortable(),
                TextColumn::make('sort_order')->label('Order')->sortable(),
                TextColumn::make('condition_type')->label('Condition')->badge()->sortable(),
                IconColumn::make('is_required')->label('Required')->boolean(),
                IconColumn::make('requires_photo')->label('Photo')->boolean(),
            ])
            ->filters([
                SelectFilter::make('job_type_id')->label('Service')->relationship('jobType', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id)),
            ])
            ->recordActions([Actions\EditAction::make()])
            ->toolbarActions([Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()])])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListJobTypeChecklistItems::route('/'), 'create' => Pages\CreateJobTypeChecklistItem::route('/create'), 'edit' => Pages\EditJobTypeChecklistItem::route('/{record}/edit')];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('jobType', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id));
    }
}
