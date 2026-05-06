<?php
namespace Modules\TitanNexus\Automation\Triggers;
class PaymentFailedTrigger { public function description(): string { return 'Detects payment failure events.'; } public function handle(array $payload = []): array { return ['handled'=>true,'payload'=>$payload]; } }
