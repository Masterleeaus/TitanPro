<?php
namespace Modules\TitanNexus\Filament\Resources;
use Modules\TitanNexus\Models\NexusPayment;
class NexusPaymentResource { public static string $model = NexusPayment::class; public static function navigationGroup(): string { return 'Titan Nexus'; } }
