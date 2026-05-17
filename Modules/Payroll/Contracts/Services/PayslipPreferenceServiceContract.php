<?php

namespace Modules\Payroll\Contracts\Services;

interface PayslipPreferenceServiceContract
{
    public function getForEmployee(int $userId): array;
    public function mergeForEmployee(int $userId, array $preferences): array;
    public function shouldDeliver(int $userId): bool;
}
