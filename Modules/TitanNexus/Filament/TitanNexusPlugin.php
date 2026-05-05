<?php
namespace Modules\TitanNexus\Filament;
use Filament\Contracts\Plugin;use Filament\Panel;use Modules\TitanNexus\Filament\Resources\LeadRecordResource;
class TitanNexusPlugin implements Plugin{public function getId():string{return 'titan-nexus';}public function register(Panel $panel):void{$panel->resources([LeadRecordResource::class]);}public function boot(Panel $panel):void{}public static function make():self{return app(self::class);}}
