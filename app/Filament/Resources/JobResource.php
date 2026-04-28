<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string|\UnitEnum|null $navigationGroup = 'Jobs';
    protected static ?string $navigationLabel = 'Jobs';
    protected static ?string $modelLabel = 'Job';
    protected static ?string $pluralModelLabel = 'Jobs';
    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool { return true; }
    public static function canEdit($record): bool { return true; }
    public static function canDelete($record): bool { return true; }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Scheduling')
                ->columnSpanFull()
                ->columns(1)
                ->schema([
                    TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                    Select::make('status')->options(Job::statuses())->default(Job::STATUS_SCHEDULED)->required(),
                    Select::make('customer_id')->label('Customer')->relationship('customer', 'last_name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)->searchable()->preload()->required(),
                    Select::make('property_id')->label('Property')->relationship('property', 'address_line1', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->searchable()->preload(),
                    Select::make('job_type_id')->label('Service')->relationship('jobType', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id)->where('is_active', true))->searchable()->preload(),
                    DateTimePicker::make('scheduled_at')->label('Scheduled Start'),
                    DateTimePicker::make('scheduled_end_at')->label('Scheduled End'),
                    Select::make('assigned_to')->label('Cleaner / Crew Lead')->relationship('assignedTechnician', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->searchable()->preload(),
                ]),
            FormSection::make('Cleaning Details')
                ->columnSpanFull()
                ->columns(1)
                ->schema([
                    TextInput::make('rooms_count')->label('Rooms')->numeric()->minValue(0),
                    TextInput::make('bathrooms_count')->label('Bathrooms')->numeric()->minValue(0)->step(0.5),
                    TextInput::make('square_metres')->label('Floor Area (m²)')->numeric()->minValue(0)->suffix('m²'),
                    Select::make('recurrence_frequency')->label('Recurring')->options([
                        'none' => 'One-time', 'weekly' => 'Weekly', 'biweekly' => 'Biweekly', 'monthly' => 'Monthly', 'custom' => 'Custom',
                    ])->default('none'),
                    Toggle::make('requires_quality_check')->label('Quality Check Required')->default(false),
                    TextInput::make('quality_score')->label('Quality Score')->numeric()->minValue(0)->maxValue(100),
                    Textarea::make('access_instructions')->label('Access Instructions')->rows(3)->columnSpanFull(),
                    Textarea::make('description')->label('Job Scope')->rows(3)->columnSpanFull(),
                    Textarea::make('customer_notes')->label('Customer Notes')->rows(3),
                    Textarea::make('office_notes')->label('Office Notes')->rows(3),
                    Textarea::make('technician_notes')->label('Cleaner Notes')->rows(3),
                ]),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job Details')->schema([
                TextEntry::make('title'),
                TextEntry::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'scheduled' => 'info', 'assigned' => 'info', 'en_route' => 'warning', 'arrived' => 'warning',
                    'in_progress' => 'warning', 'quality_check' => 'primary', 'completed' => 'success',
                    'invoiced' => 'success', 'paid' => 'success', 'cancelled' => 'danger', 'on_hold' => 'gray', default => 'gray',
                })->formatStateUsing(fn (string $state): string => Job::statuses()[$state] ?? $state),
                TextEntry::make('customer.full_name')->label('Customer'),
                TextEntry::make('property.address_line1')->label('Property'),
                TextEntry::make('jobType.name')->label('Service'),
                TextEntry::make('assignedTechnician.name')->label('Cleaner / Crew Lead'),
                TextEntry::make('scheduled_at')->label('Scheduled Start')->dateTime(),
                TextEntry::make('scheduled_end_at')->label('Scheduled End')->dateTime(),
                TextEntry::make('rooms_count')->label('Rooms'),
                TextEntry::make('bathrooms_count')->label('Bathrooms'),
                TextEntry::make('square_metres')->label('Floor Area (m²)')->suffix(' m²'),
                TextEntry::make('quality_score')->label('Quality Score'),
            ])->columns(1),
            Section::make('Notes')->schema([
                TextEntry::make('access_instructions')->label('Access Instructions')->prose(),
                TextEntry::make('description')->label('Job Scope')->prose(),
                TextEntry::make('office_notes')->label('Office Notes')->prose(),
                TextEntry::make('technician_notes')->label('Cleaner Notes')->prose(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_at')->label('Scheduled')->dateTime('M j, Y g:i A')->sortable(),
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('customer.last_name')->label('Customer')->formatStateUsing(fn ($record) => $record->customer?->full_name)->searchable(['customers.last_name', 'customers.first_name']),
                TextColumn::make('jobType.name')->label('Service')->sortable(),
                TextColumn::make('assignedTechnician.name')->label('Cleaner')->placeholder('Unassigned'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'scheduled' => 'info', 'assigned' => 'info', 'en_route' => 'warning', 'arrived' => 'warning',
                    'in_progress' => 'warning', 'quality_check' => 'primary', 'completed' => 'success',
                    'invoiced' => 'success', 'paid' => 'success', 'cancelled' => 'danger', 'on_hold' => 'gray', default => 'gray',
                })->formatStateUsing(fn (string $state): string => Job::statuses()[$state] ?? $state),
            ])
            ->filters([
                SelectFilter::make('status')->options(Job::statuses()),
                SelectFilter::make('job_type_id')->label('Service')->relationship('jobType', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id)),
                SelectFilter::make('assigned_to')->label('Cleaner')->relationship('assignedTechnician', 'name', fn (Builder $query) => $query->where('organization_id', auth()->user()?->organization_id))->placeholder('All cleaners'),
            ])
            ->defaultSort('scheduled_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view'  => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('organization_id', auth()->user()?->organization_id)
            ->with(['customer', 'assignedTechnician', 'jobType']);
    }
}
