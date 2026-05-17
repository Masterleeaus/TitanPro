<?php
namespace Modules\ExampleModule\Filament\Resources;
use Filament\Resources\Resource;use Modules\ExampleModule\Models\ExampleRecord;
class ExampleRecordResource extends Resource{protected static ?string $model=ExampleRecord::class;protected static ?string $navigationIcon='heroicon-o-squares-2x2';public static function canViewAny():bool{return auth()->user()?->can('example.view')??false;}}
