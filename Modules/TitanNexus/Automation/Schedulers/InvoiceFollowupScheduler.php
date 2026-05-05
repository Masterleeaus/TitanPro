<?php
namespace Modules\TitanNexus\Automation\Schedulers;
class InvoiceFollowupScheduler { public function description(): string { return 'Schedules daily overdue invoice scans.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
