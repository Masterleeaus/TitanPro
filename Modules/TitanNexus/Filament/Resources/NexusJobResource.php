<?php
namespace Modules\TitanNexus\Filament\Resources;
use Modules\TitanNexus\Models\NexusJob;
class NexusJobResource { public static string $model = NexusJob::class; public static function navigationGroup(): string { return 'Titan Nexus'; } }
