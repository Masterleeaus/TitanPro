<?php

namespace Modules\Payroll\Jobs\Scheduled;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetryFailedPayslipDeliveriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('payroll_payslip_delivery_audits')) {
            Log::info('Payroll retry skipped: delivery audit table missing.');
            return;
        }

        $limit = (int) config('payroll.features.delivery_retry_batch_size', 50);
        $failed = DB::table('payroll_payslip_delivery_audits')
            ->where('status', 'failed')
            ->orderBy('updated_at')
            ->limit($limit)
            ->get();

        foreach ($failed as $delivery) {
            DB::table('payroll_payslip_delivery_audits')->where('id', $delivery->id)->update([
                'status' => 'retry_queued',
                'updated_at' => now(),
            ]);
        }
    }
}
