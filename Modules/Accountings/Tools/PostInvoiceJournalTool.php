<?php

namespace Modules\Accountings\Tools;

use Modules\Accountings\Actions\PostInvoiceJournalAction;

class PostInvoiceJournalTool
{
    public function __construct(protected PostInvoiceJournalAction $action) {}

    public function name(): string { return 'accounting.post_invoice_journal'; }

    public function description(): string { return 'Posts invoice revenue, GST, and receivable journal headers using the Accountings schema.'; }

    public function __invoke(array $invoice): array
    {
        $journal = $this->action->execute($invoice);
        return method_exists($journal, 'toArray') ? $journal->toArray() : ['id' => $journal->id ?? null];
    }
}
