<?php

namespace Modules\CRMCore\Actions;

use Modules\CRMCore\Models\CRMCoreActivityLog;

class LogCRMActivity
{
    public function handle(string $event, mixed $subject = null, array $payload = []): CRMCoreActivityLog
    {
        return CRMCoreActivityLog::create([
            'company_id' => function_exists('company') ? company()?->id : null,
            'actor_id' => auth()->id(),
            'subject_type' => is_object($subject) ? $subject::class : null,
            'subject_id' => is_object($subject) && method_exists($subject, 'getKey') ? $subject->getKey() : null,
            'event' => $event,
            'payload' => $payload,
        ]);
    }
}
