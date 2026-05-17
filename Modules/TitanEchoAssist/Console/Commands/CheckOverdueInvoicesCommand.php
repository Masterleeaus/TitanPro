<?php

namespace Modules\TitanEchoAssist\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\TitanEchoAssist\Listeners\PortalAutomation\HandleInvoiceOverdue;

class CheckOverdueInvoicesCommand extends Command
{
    protected $signature = 'chatbot:portal:check-overdue-invoices';

    protected $description = 'Trigger chatbot portal automations for overdue invoices';

    public function __construct(private readonly HandleInvoiceOverdue $handler)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $now = Carbon::now(config('app.timezone', 'UTC'));
        $count = 0;

        Invoice::query()
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
            ->where('balance_due', '>', 0)
            ->whereNotNull('due_at')
            ->where('due_at', '<', $now)
            ->with('customer')
            ->chunkById(100, function ($invoices) use (&$count): void {
                foreach ($invoices as $invoice) {
                    $this->handler->handle($invoice);
                    $count++;
                }
            });

        $this->info("Processed {$count} overdue invoice portal automation trigger(s).");

        return self::SUCCESS;
    }
}
