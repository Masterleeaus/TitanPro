<?php

namespace Modules\EInvoice\Tools;

use Modules\EInvoice\Actions\GenerateLateInvoiceFollowupAction;
use Modules\EInvoice\Entities\Invoice;

class GenerateLateFollowupTool
{
    public function __construct(protected GenerateLateInvoiceFollowupAction $action) {}
    public function name(): string { return 'einvoice.generate_late_followup'; }
    public function description(): string { return 'Generates a human-approvable follow-up for an overdue invoice. It does not process payments.'; }
    public function handle(Invoice $invoice, int $daysOverdue): array { return $this->action->execute($invoice, $daysOverdue); }
}
