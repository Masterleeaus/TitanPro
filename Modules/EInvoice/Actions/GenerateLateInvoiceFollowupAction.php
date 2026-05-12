<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;
use Modules\EInvoice\Events\InvoiceEscalated;
use Modules\EInvoice\Events\InvoiceBecameDue;
use Modules\EInvoice\Events\InvoiceBecameOverdue;

class GenerateLateInvoiceFollowupAction
{
    public function execute(Invoice $invoice, int $daysOverdue): array
    {
        $stage = match (true) {
            $daysOverdue >= 30 => 'escalation_recommendation',
            $daysOverdue >= 21 => 'payment_plan',
            $daysOverdue >= 14 => 'phone_script',
            $daysOverdue >= 7  => 'email_reminder',
            default            => 'sms_reminder',
        };

        $context = [
            'company_id'  => $invoice->company_id,
            'actor_id'    => null,
            'occurred_at' => now()->toIso8601String(),
            'days_overdue' => $daysOverdue,
            'stage'        => $stage,
        ];

        if ($stage === 'escalation_recommendation') {
            event(new InvoiceEscalated($invoice, $context));
        } elseif ($daysOverdue > 0) {
            event(new InvoiceBecameOverdue($invoice, $context));
        } else {
            event(new InvoiceBecameDue($invoice, $context));
        }

        return ['stage' => $stage, 'requires_human_approval' => true, 'message' => "Generated {$stage} for invoice ".($invoice->invoice_number ?? $invoice->id)];
    }
}
