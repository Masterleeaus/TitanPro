<?php

namespace Modules\Payroll\Services\Domain;

use Illuminate\Validation\ValidationException;
use Modules\Payroll\Contracts\Services\PayrollPeriodLockServiceContract;
use Modules\Payroll\Entities\PayrollPeriodLock;

class PayrollPeriodLockService implements PayrollPeriodLockServiceContract
{
    public function lock(int $companyId, string $periodFrom, string $periodTo, array $meta = []): array
    {
        $lock = PayrollPeriodLock::query()->updateOrCreate(
            ['company_id' => $companyId, 'period_from' => $periodFrom, 'period_to' => $periodTo],
            [
                'status' => 'locked',
                'locked_by' => auth()->id(),
                'locked_at' => now(),
                'unlocked_by' => null,
                'unlocked_at' => null,
                'unlock_reason' => null,
                'metadata' => $meta,
            ]
        );

        return $lock->fresh()->toArray();
    }

    public function unlock(int $companyId, string $periodFrom, string $periodTo, string $reason, array $meta = []): array
    {
        $lock = PayrollPeriodLock::query()->firstOrNew([
            'company_id' => $companyId,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
        ]);

        $lock->fill([
            'status' => 'unlocked',
            'unlocked_by' => auth()->id(),
            'unlocked_at' => now(),
            'unlock_reason' => $reason,
            'metadata' => array_merge((array) $lock->metadata, $meta),
        ])->save();

        return $lock->fresh()->toArray();
    }

    public function assertUnlocked(int $companyId, string $periodFrom, string $periodTo): void
    {
        if ($this->isLocked($companyId, $periodFrom, $periodTo)) {
            throw ValidationException::withMessages([
                'period' => 'This payroll period is locked and cannot be modified without an unlock reason.',
            ]);
        }
    }

    public function isLocked(int $companyId, string $periodFrom, string $periodTo): bool
    {
        return PayrollPeriodLock::query()
            ->where('company_id', $companyId)
            ->where('period_from', $periodFrom)
            ->where('period_to', $periodTo)
            ->where('status', 'locked')
            ->exists();
    }
}
