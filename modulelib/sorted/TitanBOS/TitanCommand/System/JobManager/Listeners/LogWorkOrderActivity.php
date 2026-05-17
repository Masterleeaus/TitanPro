<?php

namespace App\Extensions\TitanCommand\System\JobManager\Listeners;

use Illuminate\Support\Facades\Log;





use App\Extensions\TitanCommand\System\JobManager\Events\{WorkOrderCreated, WorkOrderUpdated, WorkOrderCompleted};

class LogWorkOrderActivity
{
    public function handle($event): void
    {
        $type = is_object($event) ? class_basename($event) : 'UnknownEvent';
        try {
            $wo = $event->workOrder ?? null;
            $id = $wo ? ($wo->id ?? null) : null;
            Log::info('[JobManager] Activity', ['event'=>$type, 'work_order_id'=>$id]);
        } catch (\Throwable $e) {
            Log::warning('[JobManager] Activity log failed: '.$e->getMessage());
        }
    }
}
