<?php

namespace Modules\ZeroPayModule\Exports;

use Illuminate\Database\Eloquent\Builder;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ZeroPayTransactionsExport
{
    /** @var Builder<ZeroPayTransaction> */
    protected Builder $query;

    /** @param  Builder<ZeroPayTransaction>|null  $query */
    public function __construct(?Builder $query = null)
    {
        $this->query = $query ?? ZeroPayTransaction::query();
    }

    /** Column headings for the CSV. */
    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Gateway',
            'Gateway Reference',
            'Amount (AUD)',
            'Fee (AUD)',
            'Status',
            'Payer Name',
            'Payee Name',
            'Reference',
            'Created At',
        ];
    }

    /** Map a single transaction row to a CSV row. */
    public function map(ZeroPayTransaction $transaction): array
    {
        return [
            $transaction->id,
            $transaction->type,
            $transaction->gateway,
            $transaction->gateway_reference,
            $transaction->amount,
            $transaction->fee,
            $transaction->status,
            $transaction->payer_name,
            $transaction->payee_name,
            $transaction->reference,
            $transaction->created_at?->toDateTimeString(),
        ];
    }

    /** Build and stream the CSV response. */
    public function download(string $filename = 'transactions.csv'): StreamedResponse
    {
        $headings = $this->headings();
        $query = $this->query;

        return new StreamedResponse(function () use ($headings, $query) {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, $headings);

            $query->orderBy('id')->chunkById(500, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, $this->map($row));
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
