<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages;
use Modules\CleaningJobs\Models\WorkOrder;

class RecurringJobResource extends Resource
{
    protected static ?string $model = WorkOrder::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static string|\UnitEnum|null $navigationGroup = 'Cleaning Jobs';
    protected static ?string $navigationLabel = 'Recurring Jobs';
    protected static ?string $modelLabel = 'Recurring Job';
    protected static ?string $pluralModelLabel = 'Recurring Jobs';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 30;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereNotNull('recurrence_pattern');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Recurring Job Details')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Select::make('recurrence_pattern')
                        ->label('Recurrence')
                        ->options([
                            'daily'     => 'Daily',
                            'weekly'    => 'Weekly',
                            'biweekly'  => 'Bi-Weekly',
                            'monthly'   => 'Monthly',
                            'quarterly' => 'Quarterly',
                        ])
                        ->required(),
                    Select::make('status')
                        ->options([
                            'pending'   => 'Pending',
                            'approved'  => 'Approved',
                            'on_hold'   => 'On Hold',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending'),
                    TextInput::make('budget_amount')
                        ->numeric()
                        ->prefix('$')
                        ->label('Budget'),
                    TextInput::make('estimated_hours')
                        ->numeric()
                        ->label('Estimated Hours'),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                BadgeColumn::make('recurrence_pattern')->label('Recurrence'),
                BadgeColumn::make('status'),
                TextColumn::make('budget_amount')->money('USD')->label('Budget'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRecurringJobs::route('/'),
            'create' => Pages\CreateRecurringJob::route('/create'),
            'edit'   => Pages\EditRecurringJob::route('/{record}/edit'),
        ];
    }
}
