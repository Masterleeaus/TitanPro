<?php

namespace Modules\Accountings\Services;

use Modules\Accountings\Actions\PostInvoiceJournalAction;

class AccountingIntegrationService
{
    public function __construct(private readonly PostInvoiceJournalAction $postInvoiceJournal) {}

    public function postInvoiceIssued(array $invoicePayload)
    {
        return $this->postInvoiceJournal->execute($invoicePayload);
    }
}
