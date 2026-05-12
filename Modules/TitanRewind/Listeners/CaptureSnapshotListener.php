<?php

namespace Modules\TitanRewind\Listeners;

use Modules\Accountings\Events\InvoiceJournalPosted;
use Modules\EInvoice\Events\InvoiceSent;
use Modules\TitanRewind\Services\RewindAuditService;

class CaptureSnapshotListener
{
    public function __construct(private readonly RewindAuditService $auditService) {}

    public function handle(InvoiceJournalPosted|InvoiceSent $event): void
    {
        if ($event instanceof InvoiceJournalPosted) {
            $payload = $event->payload;

            $this->appendSnapshot(
                (int) ($payload['company_id'] ?? 0),
                'Accountings.JournalPosted',
                'journal',
                (string) ($payload['journal_id'] ?? 'unknown'),
                $payload,
            );

            return;
        }

        $companyId = (int) ($event->invoice->company_id ?? $event->context['company_id'] ?? 0);

        $this->appendSnapshot(
            $companyId,
            'EInvoice.InvoiceSent',
            'invoice',
            (string) ($event->invoice->id ?? 'unknown'),
            [
                'invoice_id' => $event->invoice->id ?? null,
                'context' => $event->context,
            ],
        );
    }

    public function handleStringEvent(string $eventName, array $payload): void
    {
        $envelope = $payload[0] ?? $payload;

        if (! is_array($envelope)) {
            return;
        }

        $companyId = (int) ($envelope['company_id'] ?? 0);

        $this->appendSnapshot(
            $companyId,
            $eventName,
            (string) ($envelope['entity_type'] ?? 'audit-event'),
            (string) ($envelope['entity_id'] ?? $envelope['id'] ?? 'unknown'),
            $envelope,
        );
    }

    private function appendSnapshot(int $companyId, string $sourceType, string $entityType, string $entityId, array $payload): void
    {
        if ($companyId <= 0) {
            return;
        }

        $this->auditService->appendEvent([
            'company_id' => $companyId,
            'case_key' => sprintf('%s:%s', $entityType, $entityId),
            'title' => sprintf('%s snapshot captured', $sourceType),
            'severity' => 'medium',
            'source_type' => $sourceType,
            'source_id' => $entityId,
            'event_type' => 'snapshot_captured',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'actor_type' => 'system',
            'payload_json' => $payload,
            'created_at' => now(),
        ]);
    }
}
