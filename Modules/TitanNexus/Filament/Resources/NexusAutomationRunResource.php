<?php
namespace Modules\TitanNexus\Filament\Resources;
use Modules\TitanNexus\Models\NexusAutomationRun;
class NexusAutomationRunResource { public static string $model = NexusAutomationRun::class; public static function navigationGroup(): string { return 'Titan Nexus'; } }
