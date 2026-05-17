<?php

namespace Modules\TitanGoField\Listeners;

use Modules\TitanGoField\Models\FsmAuditLog;

class LogFieldJobActivity
{
    public function handle(object $event): void
    {
        $job    = $event->fieldJob;
        $action = match (true) {
            $event instanceof \Modules\TitanGoField\Events\FieldJobCreated   => 'created',
            $event instanceof \Modules\TitanGoField\Events\FieldJobUpdated   => 'updated',
            $event instanceof \Modules\TitanGoField\Events\FieldJobCompleted => 'completed',
            default                                                          => 'unknown',
        };

        FsmAuditLog::create([
            'company_id'  => $job->company_id,
            'entity_type' => 'field_job',
            'entity_id'   => $job->id,
            'user_id'     => $event->actorId,
            'action'      => $action,
            'after'       => ['status' => $job->status],
            'ip'          => request()?->ip(),
        ]);
    }
}
