<?php

namespace App\Extensions\ProductPhotography\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Extensions\ProductPhotography\Models\RevenueExecutionLog;

class RevenueExecutionBridge
{
    public function preview(array $builderData): array
    {
        $mode = $builderData['mode'] ?? 'quote';

        return [
            'mode' => $mode,
            'can_create_job' => Schema::hasTable('tz_jobs') && !empty($builderData['auto_create_job']),
            'can_create_invoice' => Schema::hasTable('tz_invoices') && (($mode === 'invoice' && !empty($builderData['auto_send_invoice'])) || !empty($builderData['auto_send_invoice'])),
            'booking_strategy' => $this->bookingStrategy(),
        ];
    }

    public function execute(array $builderData, array $context = []): array
    {
        $mode = $builderData['mode'] ?? 'quote';
        $metadata = $this->decodeJson($builderData['metadata_json'] ?? null);

        $result = [
            'mode' => $mode,
            'booking_strategy' => $this->bookingStrategy(),
            'job_id' => null,
            'invoice_id' => null,
            'actions' => [],
        ];

        if (in_array($mode, ['quote', 'booking'], true) && !empty($builderData['auto_create_job'])) {
            $jobId = $this->createJob($builderData, $metadata, $context);
            $result['job_id'] = $jobId;
            $result['actions'][] = $jobId ? 'job_created' : 'job_skipped';
        }

        if ($mode === 'invoice' && !empty($builderData['auto_send_invoice'])) {
            $invoiceId = $this->createInvoice($builderData, $metadata, $context, $result['job_id']);
            $result['invoice_id'] = $invoiceId;
            $result['actions'][] = $invoiceId ? 'invoice_created' : 'invoice_skipped';
        }

        return $result;
    }

    protected function createJob(array $builderData, array $metadata, array $context): ?int
    {
        if (!Schema::hasTable('tz_jobs')) {
            return null;
        }

        $insert = [
            'team_id' => $context['team_id'] ?? null,
            'user_id' => $context['user_id'] ?? null,
            'external_ref' => 'quotemaker:' . Str::uuid(),
            'customer_id' => $context['customer_id'] ?? null,
            'site_id' => $context['site_id'] ?? null,
            'title' => $builderData['name'] ?? ($builderData['service_type'] ?? 'QuoteMaker Job'),
            'notes' => $metadata['scope_notes'] ?? $builderData['summary'] ?? null,
            'status' => ($builderData['mode'] ?? 'quote') === 'booking' ? 'booked' : 'draft',
            'priority' => 'normal',
            'risk_level' => 'low',
            'service_date' => $context['service_date'] ?? null,
            'duration_expected_min' => isset($metadata['estimated_hours']) && is_numeric($metadata['estimated_hours'])
                ? (int) round(((float) $metadata['estimated_hours']) * 60)
                : null,
            'subtotal' => $metadata['estimated_price_min'] ?? null,
            'tax' => 0,
            'total' => $metadata['estimated_price_max'] ?? ($metadata['estimated_price_min'] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $jobId = DB::table('tz_jobs')->insertGetId($insert);
        $this->logExecution($builderData, 'create_job', 'tz_jobs', $jobId, 'created', $insert, ['job_id' => $jobId]);

        return (int) $jobId;
    }

    protected function createInvoice(array $builderData, array $metadata, array $context, ?int $jobId = null): ?int
    {
        if (!Schema::hasTable('tz_invoices')) {
            return null;
        }

        $invoiceNumber = $this->nextInvoiceNumber();

        $insert = [
            'team_id' => $context['team_id'] ?? null,
            'user_id' => $context['user_id'] ?? null,
            'invoice_number' => $invoiceNumber,
            'customer_id' => $context['customer_id'] ?? null,
            'job_id' => $jobId,
            'issue_date' => now()->toDateString(),
            'due_date' => isset($metadata['due_days']) && is_numeric($metadata['due_days'])
                ? now()->addDays((int) $metadata['due_days'])->toDateString()
                : now()->toDateString(),
            'status' => 'draft',
            'subtotal' => $metadata['estimated_price_min'] ?? 0,
            'tax' => 0,
            'total' => $metadata['estimated_price_max'] ?? ($metadata['estimated_price_min'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $invoiceId = DB::table('tz_invoices')->insertGetId($insert);
        $this->logExecution($builderData, 'create_invoice', 'tz_invoices', $invoiceId, 'created', $insert, ['invoice_id' => $invoiceId]);

        return (int) $invoiceId;
    }

    protected function nextInvoiceNumber(): string
    {
        if (!Schema::hasTable('tz_invoices')) {
            return 'INV-' . now()->format('YmdHis');
        }

        $last = DB::table('tz_invoices')->orderByDesc('id')->value('invoice_number');
        if ($last && preg_match('/(\d+)$/', (string) $last, $m)) {
            return 'INV-' . str_pad((string) (((int) $m[1]) + 1), 6, '0', STR_PAD_LEFT);
        }

        return 'INV-000001';
    }

    protected function bookingStrategy(): array
    {
        return [
            'native_booking_table_found' => Schema::hasTable('tz_bookings'),
            'fallback' => 'tz_jobs status=booked when booking table is unavailable',
        ];
    }

    protected function decodeJson($value): array
    {
        if (!$value) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode((string) $value, true);
        return is_array($decoded) ? $decoded : [];
    }

    protected function logExecution(array $builderData, string $action, string $targetTable, ?int $targetId, string $status, array $payload, array $result): void
    {
        if (!Schema::hasTable('ext_quotemaker_execution_logs')) {
            return;
        }

        RevenueExecutionLog::create([
            'builder_id' => $builderData['id'] ?? null,
            'mode' => $builderData['mode'] ?? 'quote',
            'action' => $action,
            'target_table' => $targetTable,
            'target_id' => $targetId,
            'status' => $status,
            'payload_json' => json_encode($payload, JSON_UNESCAPED_SLASHES),
            'result_json' => json_encode($result, JSON_UNESCAPED_SLASHES),
        ]);
    }
}
