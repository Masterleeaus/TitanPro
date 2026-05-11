<?php
namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers;
class NotifyHandler { public function handle(array $payload=[]): array { return ['status'=>'notified','payload'=>$payload]; } }
