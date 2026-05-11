<?php
namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services\Handlers;
class ActionHandlerManager {
    public function dispatch(string $action, array $payload=[]): array {
        return match($action) {
            'notify' => (new NotifyHandler())->handle($payload),
            'webhook' => (new WebhookHandler())->handle($payload),
            'core.task.create' => (new CreateTaskHandler())->handle($payload),
            default => ['status'=>'unknown_action','action'=>$action,'payload'=>$payload],
        };
    }
}
