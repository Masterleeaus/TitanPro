<?php

namespace Modules\Payroll\Services\Notifications;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Payroll\Contracts\Services\PayslipDeliveryAuditServiceContract;
use Modules\Payroll\Support\DTOs\PayslipDeliveryResult;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipDeliveryAuditService implements PayslipDeliveryAuditServiceContract
{
    public function recordAttempt(PayslipDocument $document, array $employee, array $options = []): string
    {
        $deliveryId = (string) Str::uuid();

        if (config('payroll.features.persist_payslip_delivery_audit', true)) {
            DB::table('payroll_payslip_deliveries')->insert([
                'uuid' => $deliveryId,
                'user_id' => $document->userId,
                'salary_slip_id' => $document->salarySlipId,
                'recipient' => $employee['email'] ?? $employee['work_email'] ?? null,
                'channels' => json_encode($options['channels'] ?? []),
                'status' => 'queued',
                'meta' => json_encode(['period_from' => $document->periodFrom, 'period_to' => $document->periodTo]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $deliveryId;
    }

    public function recordResult(string $deliveryId, PayslipDeliveryResult $result, array $meta = []): void
    {
        if (! config('payroll.features.persist_payslip_delivery_audit', true)) {
            return;
        }

        DB::table('payroll_payslip_deliveries')->where('uuid', $deliveryId)->update([
            'status' => $result->status,
            'recipient' => $result->recipient,
            'channels' => json_encode($result->channels),
            'error' => $result->error,
            'meta' => json_encode(array_merge($result->meta, $meta)),
            'sent_at' => $result->status === 'delivered' ? now() : null,
            'updated_at' => now(),
        ]);
    }

    public function acknowledge(string $deliveryId, int $userId, array $meta = []): array
    {
        $payload = [
            'delivery_uuid' => $deliveryId,
            'user_id' => $userId,
            'ip_address' => $meta['ip_address'] ?? null,
            'user_agent' => $meta['user_agent'] ?? null,
            'meta' => json_encode($meta),
            'acknowledged_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (config('payroll.features.persist_payslip_delivery_audit', true)) {
            DB::table('payroll_payslip_acknowledgements')->updateOrInsert(
                ['delivery_uuid' => $deliveryId, 'user_id' => $userId],
                $payload
            );
            DB::table('payroll_payslip_deliveries')->where('uuid', $deliveryId)->update([
                'acknowledged_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $payload;
    }
}
