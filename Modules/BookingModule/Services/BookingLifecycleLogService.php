<?php

namespace Modules\BookingModule\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Modules\BookingModule\Entities\BookingLifecycleLog;

class BookingLifecycleLogService
{
    public function record(
        Model|string $subject,
        ?int $subjectId,
        string $event,
        ?int $companyId = null,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        array $payload = [],
        ?int $actorId = null,
    ): ?BookingLifecycleLog {
        if (!Schema::hasTable('booking_lifecycle_logs')) {
            return null;
        }

        if ($subject instanceof Model) {
            $subjectType = $subject::class;
            $subjectId = $subjectId ?: (int) $subject->getKey();
            $companyId = $companyId ?: (int) ($subject->company_id ?? 0) ?: null;
        } else {
            $subjectType = $subject;
        }

        if (!$subjectId) {
            return null;
        }

        return BookingLifecycleLog::create([
            'company_id' => $companyId,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'event' => $event,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'actor_id' => $actorId,
            'payload' => $payload ?: null,
            'occurred_at' => now(),
        ]);
    }
}
