<?php

namespace Modules\CRMCore\Filament\Resources;

use App\Models\Customer;
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
use Modules\CRMCore\Filament\Resources\DealProjectResource\Pages;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;

class DealProjectResource extends Resource
{
    protected static ?string $model = Deal::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Deals';
    protected static ?string $modelLabel = 'Deal';
    protected static ?string $pluralModelLabel = 'Deals';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Deal')
                ->columns(1)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Select::make('crmcore_customer_id')
                        ->label('Customer')
                        ->options(fn () => Customer::query()
                            ->orderBy('first_name')
                            ->orderBy('last_name')
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(fn ($customer) => [$customer->getKey() => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')) . ($customer->email ? ' — ' . $customer->email : '')])
                            ->filter(fn ($label) => filled(trim((string) $label)))
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
                        ->options(fn () => static::pipelineOptions())
                        ->default(fn () => static::defaultPipelineId())
                        ->required(),
                    Select::make('deal_stage_id')
                        ->label('Stage')
                        ->options(fn () => static::stageOptions())
                        ->default(fn () => static::defaultStageId())
                        ->required(),
                    TextInput::make('probability')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%'),
                    DatePicker::make('expected_close_date'),
                    Select::make('assigned_to_user_id')
                        ->label('Assigned To')
                        ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('crmcore_service_interest')
                        ->label('Service Interest')
                        ->maxLength(255),
                    TextInput::make('crmcore_project_id')
                        ->numeric()
                        ->label('Created Job ID')
                        ->disabled()
                        ->dehydrated(false),
                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Deal')->searchable()->sortable(),
                TextColumn::make('crmcoreCustomer.full_name')->label('Customer')->searchable(['first_name', 'last_name'])->toggleable(),
                TextColumn::make('stage.name')->label('Stage')->badge()->sortable(),
                TextColumn::make('value')->money('USD')->sortable(),
                TextColumn::make('crmcore_service_interest')->label('Service')->searchable(),
                TextColumn::make('expected_close_date')->date()->sortable()->toggleable(),
                TextColumn::make('crmcore_project_id')->label('Job ID')->sortable(),
                TextColumn::make('crmcore_converted_to_project_at')->label('Converted')->dateTime()->sortable(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\Action::make('createProject')
                    ->label('Create Job')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn ($record) => app(\Modules\CRMCore\Actions\CreateProjectFromDeal::class)->handle($record)),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDealProjects::route('/'),
            'create' => Pages\CreateDealProject::route('/create'),
            'edit' => Pages\EditDealProject::route('/{record}/edit'),
        ];
    }

    public static function defaultPipelineId(): ?int
    {
        return DealPipeline::query()->where('is_default', true)->value('id')
            ?? DealPipeline::query()->orderBy('position')->value('id')
            ?? DealPipeline::query()->create([
                'name' => 'Sales Pipeline',
                'description' => 'Default CRM sales pipeline',
                'is_default' => true,
                'is_active' => true,
                'position' => 1,
            ])->getKey();
    }

    public static function pipelineOptions(): array
    {
        static::defaultPipelineId();

        return DealPipeline::query()->orderBy('position')->orderBy('name')->pluck('name', 'id')->all();
    }

    public static function defaultStageId(): ?int
    {
        $pipelineId = static::defaultPipelineId();

        return DealStage::query()->where('pipeline_id', $pipelineId)->where('is_default_for_pipeline', true)->value('id')
            ?? DealStage::query()->where('pipeline_id', $pipelineId)->orderBy('position')->value('id')
            ?? DealStage::query()->create([
                'pipeline_id' => $pipelineId,
                'name' => 'New',
                'color' => '#3b82f6',
                'position' => 1,
                'is_default_for_pipeline' => true,
                'is_won_stage' => false,
                'is_lost_stage' => false,
            ])->getKey();
    }

    public static function stageOptions(): array
    {
        static::defaultStageId();

        return DealStage::query()->orderBy('position')->orderBy('name')->pluck('name', 'id')->all();
    }
}
