<?php

namespace Modules\Payroll\Services\Notifications;

use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\PayslipPreferenceServiceContract;

class PayslipPreferenceService implements PayslipPreferenceServiceContract
{
    public function getForEmployee(int $userId): array
    {
        $defaults = config('payroll.notifications.employee_payslip_preferences', [
            'enabled' => true,
            'channels' => ['mail', 'database'],
            'require_acknowledgement' => true,
        ]);

        if (! config('payroll.features.persist_payslip_delivery_audit', true)) {
            return $defaults;
        }

        $row = DB::table('payroll_payslip_delivery_preferences')->where('user_id', $userId)->first();
        if (! $row) {
            return $defaults;
        }

        return array_merge($defaults, json_decode($row->preferences ?? '{}', true) ?: []);
    }

    public function mergeForEmployee(int $userId, array $preferences): array
    {
        $merged = array_merge($this->getForEmployee($userId), $preferences);

        if (config('payroll.features.persist_payslip_delivery_audit', true)) {
            DB::table('payroll_payslip_delivery_preferences')->updateOrInsert(
                ['user_id' => $userId],
                ['preferences' => json_encode($merged), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        return $merged;
    }

    public function shouldDeliver(int $userId): bool
    {
        return (bool) ($this->getForEmployee($userId)['enabled'] ?? true);
    }
}
