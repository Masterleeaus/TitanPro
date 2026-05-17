<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollPeriodLockServiceContract
{
    public function lock(int $companyId, string $periodFrom, string $periodTo, array $meta = []): array;
    public function unlock(int $companyId, string $periodFrom, string $periodTo, string $reason, array $meta = []): array;
    public function assertUnlocked(int $companyId, string $periodFrom, string $periodTo): void;
    public function isLocked(int $companyId, string $periodFrom, string $periodTo): bool;
}
