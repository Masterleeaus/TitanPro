<?php

namespace App\Filament\TitanSolo\Resources;

use App\Filament\TitanSolo\Resources\JobResource\Pages;
use App\Models\Job;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Jobs';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        $organizationId = auth()->user()?->organization_id;

        return $schema->components([
            Section::make('Job')
                ->columns(['sm' => 1, 'lg' => 2])
                ->schema([
                    TextInput::make('title')->required()->maxLength(255),
                    Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'last_name', fn (Builder $query) => $query->where('organization_id', $organizationId))
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->options(Job::statuses())
                        ->default(Job::STATUS_SCHEDULED)
                        ->required(),
                    DateTimePicker::make('scheduled_at')->label('Scheduled Time'),
                    Textarea::make('description')->label('Job Notes')->rows(3)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('customer.last_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, Job $record) => $record->customer?->full_name)
                    ->searchable(['customers.first_name', 'customers.last_name']),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Job::statuses()[$state] ?? $state),
                TextColumn::make('scheduled_at')->label('Scheduled')->dateTime('M j, Y g:i A')->sortable(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->defaultSort('scheduled_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->where('organization_id', $organizationId)
            ->with('customer');
    }
}
