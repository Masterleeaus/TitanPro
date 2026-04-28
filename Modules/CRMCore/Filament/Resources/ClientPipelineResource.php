<?php

namespace Modules\CRMCore\Filament\Resources;

use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\CRMCore\Filament\Resources\ClientPipelineResource\Pages;

class ClientPipelineResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Customer Pipeline';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 10;
    public static function form(Schema $schema): Schema { return $schema->components([Section::make('Customer Pipeline')->schema([])]); }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('full_name')->label('Customer')->searchable(['first_name', 'last_name'])->sortable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('phone')->searchable()->toggleable(),
            TextColumn::make('mobile')->searchable()->toggleable(),
            TextColumn::make('organization.name')->label('Organization')->toggleable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->recordActions([Actions\ViewAction::make()]);
    }
    public static function getPages(): array { return ['index' => Pages\ListClientPipelines::route('/'), 'view' => Pages\ViewClientPipeline::route('/{record}')]; }
}
