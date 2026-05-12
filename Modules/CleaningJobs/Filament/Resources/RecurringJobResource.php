<?php

namespace Modules\CleaningJobs\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\CleaningJobs\Models\WorkOrder;

class RecurringJobResource extends Resource
{
    protected static ?string $model = WorkOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Recurring Jobs';
    protected static ?string $navigationGroup = 'Cleaning Jobs';
    protected static ?string $slug = 'recurring-jobs';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'recurring');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Textarea::make('description')->rows(3),
            Forms\Components\Select::make('notes')
                ->label('Frequency')
                ->options([
                    'daily' => 'Daily',
                    'weekly' => 'Weekly',
                    'fortnightly' => 'Fortnightly',
                    'monthly' => 'Monthly',
                ])->required(),
            Forms\Components\DateTimePicker::make('scheduled_for')->label('Next Scheduled'),
            Forms\Components\Hidden::make('type')->default('recurring'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('notes')->label('Frequency'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'open',
                        'info' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('scheduled_for')->label('Next Scheduled')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages\ListRecurringJobs::route('/'),
            'create' => \Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages\CreateRecurringJob::route('/create'),
            'edit' => \Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages\EditRecurringJob::route('/{record}/edit'),
        ];
    }
}
