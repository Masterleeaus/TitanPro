<?php

namespace Modules\Payroll\Services\Domain;

use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\EmployeeSelfServicePayrollContract;

class EmployeeSelfServicePayrollService implements EmployeeSelfServicePayrollContract
{
    public function payslipsFor(int $userId, array $filters = []): array
    {
        if (! config('payroll.cleaning.self_service.allow_payslip_download', true)) {
            return ['enabled' => false, 'items' => []];
        }

        $limit = min((int) ($filters['limit'] ?? 12), 50);
        $query = DB::table('salary_slips')->where('user_id', $userId)->orderByDesc('created_at')->limit($limit);

        if (! empty($filters['year'])) {
            $query->whereYear('created_at', (int) $filters['year']);
        }

        return [
            'enabled' => true,
            'items' => $query->get()->map(fn ($row) => [
                'id' => $row->id ?? null,
                'month' => $row->month ?? null,
                'year' => $row->year ?? null,
                'net_salary' => $row->net_salary ?? $row->net_pay ?? null,
                'gross_salary' => $row->gross_salary ?? $row->gross_pay ?? null,
                'status' => $row->status ?? 'created',
                'created_at' => $row->created_at ?? null,
            ])->all(),
        ];
    }

    public function bankDetailsStatus(int $userId): array
    {
        return [
            'user_id' => $userId,
            'can_update' => (bool) config('payroll.cleaning.self_service.allow_bank_detail_update', true),
            'requires_approval' => (bool) config('payroll.cleaning.self_service.require_bank_change_approval', true),
            'status' => 'not_connected',
        ];
    }

    public function updateBankDetails(int $userId, array $payload): array
    {
        if (! config('payroll.cleaning.self_service.allow_bank_detail_update', true)) {
            return ['accepted' => false, 'reason' => 'Bank detail updates are disabled.'];
        }

        return [
            'accepted' => true,
            'user_id' => $userId,
            'requires_approval' => (bool) config('payroll.cleaning.self_service.require_bank_change_approval', true),
            'masked_account' => $this->mask((string) ($payload['account_number'] ?? '')),
        ];
    }

    public function taxDeclarationStatus(int $userId): array
    {
        return [
            'user_id' => $userId,
            'required' => true,
            'status' => 'pending_upload',
        ];
    }

    private function mask(string $value): string
    {
        if ($value === '') {
            return '';
        }

        return str_repeat('*', max(strlen($value) - 4, 0)).substr($value, -4);
    }
}
