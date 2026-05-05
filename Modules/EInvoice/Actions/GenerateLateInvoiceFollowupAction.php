<?php

namespace Modules\EInvoice\Actions;

use Modules\EInvoice\Entities\Invoice;

class GenerateLateInvoiceFollowupAction
{
    public function execute(Invoice $invoice, int $daysOverdue): array
    {
        $stage = match (true) {
            $daysOverdue >= 30 => 'escalation_recommendation',
            $daysOverdue >= 21 => 'payment_plan',
            $daysOverdue >= 14 => 'phone_script',
            $daysOverdue >= 7 => 'email_reminder',
            default => 'sms_reminder',
        };

        return ['stage' => $stage, 'requires_human_approval' => true, 'message' => "Generated {$stage} for invoice ".($invoice->invoice_number ?? $invoice->id)];
    }
}
