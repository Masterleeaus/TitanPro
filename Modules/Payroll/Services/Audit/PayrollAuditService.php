<?php

namespace Modules\Payroll\Services\Audit;

use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Entities\PayrollAuditLog;
use Modules\Payroll\Entities\PayrollRun;

class PayrollAuditService
{
    public function record(int $companyId, string $event, ?Model $subject = null, ?int $actorId = null, ?array $before = null, ?array $after = null, array $metadata = []): PayrollAuditLog
    {
        return PayrollAuditLog::query()->create([
            'company_id' => $companyId,
            'payroll_run_id' => $subject instanceof PayrollRun ? $subject->id : ($metadata['payroll_run_id'] ?? null),
            'actor_id' => $actorId,
            'event' => $event,
            'auditable_type' => $subject ? $subject::class : null,
            'auditable_id' => $subject?->getKey(),
            'before' => $before,
            'after' => $after,
            'metadata' => $metadata,
        ]);
    }
}
