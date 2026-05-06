<?php
namespace Modules\TitanNexus\Filament\Resources;
use Modules\TitanNexus\Models\NexusInvoice;
class NexusInvoiceResource { public static string $model = NexusInvoice::class; public static function navigationGroup(): string { return 'Titan Nexus'; } }
