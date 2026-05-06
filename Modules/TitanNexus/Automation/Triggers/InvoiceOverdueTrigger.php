<?php
namespace Modules\TitanNexus\Automation\Triggers;
class InvoiceOverdueTrigger { public function description(): string { return 'Detects overdue invoice threshold crossings.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
