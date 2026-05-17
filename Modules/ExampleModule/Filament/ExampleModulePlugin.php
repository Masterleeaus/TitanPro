<?php
namespace Modules\ExampleModule\Filament;
use Filament\Contracts\Plugin;use Filament\Panel;use Modules\ExampleModule\Filament\Resources\ExampleRecordResource;
class ExampleModulePlugin implements Plugin{public function getId():string{return 'example-module';}public function register(Panel $panel):void{$panel->resources([ExampleRecordResource::class]);}public function boot(Panel $panel):void{}public static function make():self{return app(self::class);}}
