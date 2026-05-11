<?php

namespace Modules\Accountings\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Entities\Journal;

class PostInvoiceJournalAction
{
    public function execute(array $invoicePayload): Journal
    {
        $companyId = $this->resolveCompanyId(isset($invoicePayload['company_id']) ? (int) $invoicePayload['company_id'] : null);
        $invoiceId = $invoicePayload['invoice_id'] ?? null;
        $reference = $invoicePayload['reference'] ?? ($invoiceId ? 'INV-'.$invoiceId : 'EINVOICE');

        $data = [
            'company_id' => $companyId,
            'no_journal' => $invoicePayload['journal_number'] ?? ('EINV-'.now()->format('YmdHis')),
            'journal_date' => $invoicePayload['date'] ?? now()->toDateString(),
            'reff_journal' => $reference,
            'remark' => $invoicePayload['description'] ?? 'Invoice issued from EInvoice / ZeroPay automation',
            'typejournal_id' => $invoicePayload['typejournal_id'] ?? null,
        ];

        $journal = Journal::create($data);

        // Preserve integration metadata when the host app has added those columns.
        $optional = array_filter([
            'amount' => $invoicePayload['amount'] ?? null,
            'source_type' => 'einvoice',
            'source_id' => $invoiceId,
        ], static fn ($value) => $value !== null);

        foreach ($optional as $column => $value) {
            if (Schema::hasColumn($journal->getTable(), $column)) {
                $journal->{$column} = $value;
            }
        }

        if ($journal->isDirty()) {
            $journal->save();
        }

        return $journal;
    }

    private function resolveCompanyId(?int $requestedCompanyId = null): int
    {
        $authCompanyId = auth()->user()->company_id ?? null;

        if ($authCompanyId !== null) {
            if ($requestedCompanyId !== null && $requestedCompanyId !== (int) $authCompanyId) {
                throw new AuthorizationException('Cross-tenant journal posting is not allowed.');
            }

            return (int) $authCompanyId;
        }

        if ($requestedCompanyId !== null) {
            return $requestedCompanyId;
        }

        throw new AuthorizationException('Company context is required.');
    }
}
