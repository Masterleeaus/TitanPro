<?php

namespace Modules\Security\Security\Audit;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class SecurityAuditLogger
{
    public function record(string $event, ?Model $subject = null, array $context = []): void
    {
        Log::channel(config('security.audit.log_channel', config('logging.default')))->info('security.audit', [
            'event' => $event,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'context' => $context,
            'module' => 'security',
        ]);
    }
}
