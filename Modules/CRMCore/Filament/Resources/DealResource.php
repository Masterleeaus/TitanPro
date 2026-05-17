<?php

namespace Modules\CRMCore\Filament\Resources;

use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CRMCore\Actions\Deal\CreateDealAction;
use Modules\CRMCore\Actions\Deal\LoseDealAction;
use Modules\CRMCore\Actions\Deal\UpdateDealAction;
use Modules\CRMCore\Actions\Deal\WinDealAction;
use Modules\CRMCore\Filament\Resources\DealResource\Pages;
use Modules\CRMCore\Models\Contact;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;

class DealResource extends Resource
{
    protected static ?string $model = Deal::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Deals';
    protected static ?string $modelLabel = 'Deal';
    protected static ?string $pluralModelLabel = 'Deals';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 25;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Deal Details')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Select::make('contact_id')
                        ->label('Contact')
                        ->options(fn () => Contact::query()
                            ->orderBy('first_name')
                            ->limit(200)
                            ->get()
                            ->mapWithKeys(fn ($c) => [$c->getKey() => $c->full_name . ($c->email_primary ? ' — ' . $c->email_primary : '')])
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('value')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),
                    TextInput::make('currency')
                        ->maxLength(3)
                        ->default('USD'),
                    Select::make('pipeline_id')
                        ->label('Pipeline')
                        ->options(fn () => DealPipeline::query()->orderBy('position')->pluck('name', 'id')->all())
                        ->required()
                        ->reactive(),
                    Select::make('deal_stage_id')
                        ->label('Stage')
                        ->options(fn ($get) => DealStage::query()
                            ->when($get('pipeline_id'), fn ($q, $pid) => $q->where('pipeline_id', $pid))
                            ->orderBy('position')
                            ->pluck('name', 'id')
                            ->all())
                        ->required(),
                    TextInput::make('probability')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%'),
                    DatePicker::make('expected_close_date')
                        ->label('Expected Close'),
                    Select::make('assigned_to_user_id')
                        ->label('Assigned To')
                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Deal::query())
            ->columns([
                TextColumn::make('title')->label('Deal')->searchable()->sortable(),
                TextColumn::make('contact.full_name')->label('Contact')->searchable(['first_name', 'last_name'])->toggleable(),
                TextColumn::make('stage.name')->label('Stage')->badge()->sortable(),
                TextColumn::make('value')->money('USD')->sortable(),
                TextColumn::make('probability')->suffix('%')->sortable()->toggleable(),
                TextColumn::make('expected_close_date')->label('Close Date')->date()->sortable()->toggleable(),
                TextColumn::make('assignedToUser.name')->label('Assigned')->toggleable(),
                TextColumn::make('won_at')->label('Won')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('lost_at')->label('Lost')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\Action::make('win')
                    ->label('Mark Won')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Deal $record): bool => ! filled($record->won_at) && ! filled($record->lost_at))
                    ->action(fn (Deal $record) => app(WinDealAction::class)->handle($record)),
                Actions\Action::make('lose')
                    ->label('Mark Lost')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Deal $record): bool => ! filled($record->won_at) && ! filled($record->lost_at))
                    ->action(fn (Deal $record) => app(LoseDealAction::class)->handle($record)),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDeals::route('/'),
            'create' => Pages\CreateDeal::route('/create'),
            'edit'   => Pages\EditDeal::route('/{record}/edit'),
        ];
    }
}

