<?php

namespace Modules\TitanGoField\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\TitanGoField\Events\FieldJobCompleted;
use Modules\TitanGoField\Models\FsmSetting;

class FieldJobAutoInvoiceListener
{
    public function handle(FieldJobCompleted $event): void
    {
        $job     = $event->fieldJob;
        $setting = FsmSetting::forCompany($job->company_id);

        if (! data_get($setting->features, 'auto_invoice', false)) {
            return;
        }

        $parts = DB::table('field_job_part_usages')
            ->where('field_job_id', $job->id)
            ->get();

        if ($parts->isEmpty()) {
            return;
        }

        $lines = $parts->map(fn ($p) => [
            'item_name'  => $p->item_name ?? 'Item #'.$p->item_id,
            'quantity'   => (float) ($p->qty ?? 0),
            'unit_price' => (float) ($p->unit_price ?? 0),
            'amount'     => (float) ($p->qty ?? 0) * (float) ($p->unit_price ?? 0),
        ]);

        $total = $lines->sum('amount');

        $invoiceId = DB::table('invoices')->insertGetId([
            'company_id'  => $job->company_id,
            'client_id'   => $job->client_id,
            'issue_date'  => now(),
            'due_date'    => now()->addDays(14),
            'sub_total'   => $total,
            'total'       => $total,
            'status'      => 'draft',
            'note'        => 'Auto-generated from Field Job #'.$job->id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        foreach ($lines as $line) {
            DB::table('invoice_items')->insert([
                'invoice_id' => $invoiceId,
                ...$line,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Log::info("TitanGoField: Job #{$job->id} auto-invoiced as invoice #{$invoiceId}");
    }
}
