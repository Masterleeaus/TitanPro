<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Console\Commands;

use App\Extensions\TitanLeads\Modules\TitanNexus\Models\InvoiceFollowup;
use App\Extensions\TitanLeads\Modules\TitanNexus\Services\Outbox\OutboxService;
use Illuminate\Console\Command;

class RunInvoiceFollowupsCommand extends Command
{
    protected $signature = 'titan-leads:run-invoice-followups';
    protected $description = 'Send due invoice follow-ups (SMS/Email) based on ext_invoice_followups rules.';

    public function handle(OutboxService $outbox): int
    {
        $now = now();
        $rows = InvoiceFollowup::query()
            ->whereIn('status', ['pending', 'reminded', 'overdue'])
            ->whereNotNull('next_followup_at')
            ->where('next_followup_at', '<=', $now)
            ->limit(250)
            ->get();

        foreach ($rows as $row) {
            $rules = $row->rules ?? [];
            $channel = $rules['channel'] ?? ($row->customer_phone ? 'sms' : 'email');

            $to = $channel === 'email' ? $row->customer_email : $row->customer_phone;
            if (!$to) {
                $row->update(['next_followup_at' => $now->addDay()]);
                continue;
            }

            $body = $rules['message'] ?? $this->defaultMessage($row);
            $subject = $rules['subject'] ?? 'Invoice reminder';

            $draft = $outbox->createDraft([
                'user_id' => $row->user_id,
                'channel' => $channel,
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
                'status' => 'draft',
                'is_ai_generated' => false,
                'requires_approval' => false,
            ]);

            // Invoice follow-ups are operational, not AI-generated; send now.
            $outbox->sendNow($draft, true);

            $row->update([
                'last_reminded_at' => $now,
                'status' => $row->due_date && $row->due_date->isPast() ? 'overdue' : 'reminded',
                'next_followup_at' => $now->copy()->addDays((int)($rules['repeat_days'] ?? 3)),
            ]);
        }

        $this->info('Processed ' . $rows->count() . ' follow-ups');
        return self::SUCCESS;
    }

    private function defaultMessage(InvoiceFollowup $row): string
    {
        $amount = $row->amount_due !== null ? number_format((float)$row->amount_due, 2) : '';
        $due = $row->due_date ? $row->due_date->format('Y-m-d') : '';
        return trim("Hi, just a reminder your invoice {$row->invoice_ref} is due {$due}. Amount due: {$amount}. Reply if you need help.");
    }
}
