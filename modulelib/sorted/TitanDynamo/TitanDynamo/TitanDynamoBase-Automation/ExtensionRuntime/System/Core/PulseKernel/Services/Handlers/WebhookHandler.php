<?php
namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers;
class WebhookHandler { public function handle(array $payload=[]): array { return ['status'=>'webhook_stub','payload'=>$payload]; } }
