<?php

namespace Modules\TitanEchoAssist\Services;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Jobs\SendQuoteFollowupNotification;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;
use Modules\TitanEchoAssist\Models\ChatbotPortalAutomationLog;
use Modules\TitanEchoAssist\Models\ChatbotPortalNotification;

class ChatbotPortalAutomationService
{
    public function trigger(string $event, array $payload): void
    {
        match ($event) {
            'job_completed' => $this->requestFeedback($payload),
            'invoice_overdue' => $this->sendPaymentReminder($payload),
            'quote_sent' => $this->scheduleQuoteFollowup($payload),
            'visit_tomorrow' => $this->sendAccessCheckPrompt($payload),
            default => null,
        };
    }

    public function processQuoteFollowup(array $payload): void
    {
        $estimate = $this->resolveEstimate($payload);
        if (! $estimate instanceof Estimate) {
            return;
        }

        // Product rule: follow-up applies when quote is still not accepted,
        // including both draft and sent lifecycle states.
        if (! in_array($estimate->status, [Estimate::STATUS_DRAFT, Estimate::STATUS_SENT], true)) {
            $this->storeAutomationLog('quote_sent', 'followup_after_24h', $payload, [
                'estimate_id' => $estimate->id,
                'idempotency_key' => $this->idempotencyKey('quote_sent', $payload),
            ], 'skipped');

            return;
        }

        $this->createNotification(
            'quote_sent',
            'followup_after_24h',
            $payload,
            'Quote follow-up',
            'Just checking in — have you had a chance to review your quote?',
            $estimate->id,
            '/portal/quotes/'.$estimate->id
        );
    }

    private function requestFeedback(array $payload): void
    {
        $job = $this->resolveJob($payload);

        $this->createNotification(
            'job_completed',
            'request_feedback',
            $payload,
            'Rate your clean',
            'How did your clean go? Tap to leave a review ⭐',
            $job?->id,
            $job ? '/portal/jobs/'.$job->id.'/feedback' : '/portal/feedback'
        );
    }

    private function sendPaymentReminder(array $payload): void
    {
        $invoice = $this->resolveInvoice($payload);
        $number = $invoice?->invoice_number ?? $payload['invoice_number'] ?? 'N/A';
        $amount = number_format((float) ($invoice?->balance_due ?? $payload['balance_due'] ?? 0), 2, '.', ',');

        $this->createNotification(
            'invoice_overdue',
            'send_payment_reminder',
            $payload,
            'Invoice overdue',
            "Invoice #{$number} for \${$amount} is overdue. Tap to pay.",
            $invoice?->id,
            $invoice ? '/portal/invoices/'.$invoice->id : '/portal/invoices'
        );
    }

    private function scheduleQuoteFollowup(array $payload): void
    {
        $estimate = $this->resolveEstimate($payload);
        if (! $estimate instanceof Estimate) {
            return;
        }

        SendQuoteFollowupNotification::dispatch([
            ...$payload,
            'estimate_id' => $estimate->id,
            'organization_id' => $estimate->organization_id,
            'customer_id' => $estimate->customer_id,
        ])->delay(now()->addHours(24));

        $this->storeAutomationLog(
            'quote_sent',
            'followup_after_24h',
            [
                ...$payload,
                'estimate_id' => $estimate->id,
                'idempotency_key' => $this->idempotencyKey('quote_sent', $payload),
            ],
            ['scheduled_for' => now()->addHours(24)->toDateTimeString()],
            'scheduled'
        );
    }

    private function sendAccessCheckPrompt(array $payload): void
    {
        $job = $this->resolveJob($payload);
        $time = $job?->scheduled_at?->format('g:i A') ?: ($payload['scheduled_time'] ?? 'your scheduled time');

        $this->createNotification(
            'visit_tomorrow',
            'send_access_check_prompt',
            $payload,
            'Visit reminder',
            "Your clean is tomorrow at {$time}. Any access instructions to update?",
            $job?->id,
            $job ? '/portal/jobs/'.$job->id : '/portal/visits'
        );
    }

    private function createNotification(
        string $event,
        string $action,
        array $payload,
        string $title,
        string $body,
        ?int $resourceId = null,
        ?string $actionUrl = null,
    ): void {
        $chatbot = $this->resolveChatbot($payload);
        $idempotencyKey = $this->idempotencyKey($event, $payload);
        if (! $chatbot instanceof Chatbot || $this->hasProcessedAutomation($chatbot->id, $event, $action, $idempotencyKey)) {
            return;
        }

        $channel = $payload['channel'] ?? 'in_app';

        $notification = ChatbotPortalNotification::query()->create([
            'company_id' => $this->resolveCompanyId($chatbot, $payload),
            'chatbot_id' => $chatbot->id,
            'customer_id' => $this->resolveChatbotCustomerId($chatbot, $payload),
            'event_type' => $event,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
            'channel' => $channel,
            'sent_at' => now(),
        ]);

        $this->storeAutomationLog($event, $action, [
            ...$payload,
            'idempotency_key' => $idempotencyKey,
            'resource_id' => $resourceId,
        ], [
            'notification_id' => $notification->id,
            'channel' => $channel,
            'template' => $body,
        ], 'success');
    }

    private function hasProcessedAutomation(int $chatbotId, string $event, string $action, string $idempotencyKey): bool
    {
        return ChatbotPortalAutomationLog::query()
            ->where('chatbot_id', $chatbotId)
            ->where('trigger_event', $event)
            ->where('action_taken', $action)
            ->where('outcome', 'success')
            ->whereJsonContains('trigger_payload->idempotency_key', $idempotencyKey)
            ->exists();
    }

    private function storeAutomationLog(string $event, string $action, array $triggerPayload, array $actionPayload, string $outcome): void
    {
        $chatbot = $this->resolveChatbot($triggerPayload);
        if (! $chatbot instanceof Chatbot) {
            return;
        }

        ChatbotPortalAutomationLog::query()->create([
            'company_id' => $this->resolveCompanyId($chatbot, $triggerPayload),
            'chatbot_id' => $chatbot->id,
            'trigger_event' => $event,
            'trigger_payload' => $triggerPayload,
            'action_taken' => $action,
            'action_payload' => $actionPayload,
            'outcome' => $outcome,
            'processed_at' => now(),
        ]);
    }

    private function resolveCompanyId(Chatbot $chatbot, array $payload): ?int
    {
        return $payload['company_id']
            ?? $payload['organization_id']
            ?? $chatbot->company_id;
    }

    private function resolveChatbot(array $payload): ?Chatbot
    {
        $chatbotId = $payload['chatbot_id'] ?? null;
        if ($chatbotId !== null) {
            return Chatbot::query()->find($chatbotId);
        }

        $companyId = $payload['company_id'] ?? $payload['organization_id'] ?? null;
        if ($companyId === null) {
            return null;
        }

        if (! Schema::hasColumn('ext_chatbots', 'company_id')) {
            return null;
        }

        return Chatbot::query()
            ->where('active', true)
            ->where('company_id', $companyId)
            ->first();
    }

    private function resolveChatbotCustomerId(Chatbot $chatbot, array $payload): ?int
    {
        if (isset($payload['chatbot_customer_id'])) {
            return (int) $payload['chatbot_customer_id'];
        }

        $email = trim($payload['customer_email'] ?? '');
        $phone = trim($payload['customer_phone'] ?? '');
        if ($email === '' && $phone === '') {
            return null;
        }

        $query = ChatbotCustomer::query()
            ->where('chatbot_id', $chatbot->id);

        if ($email !== '') {
            $emailMatch = (clone $query)->where('email', $email)->value('id');
            if ($emailMatch !== null) {
                return $emailMatch;
            }
        }

        if ($phone !== '') {
            return (clone $query)->where('phone', $phone)->value('id');
        }

        return null;
    }

    private function idempotencyKey(string $event, array $payload): string
    {
        $fallbackHash = hash('sha256', json_encode($payload));

        return match ($event) {
            'job_completed' => 'job_completed:'.($payload['job_id'] ?? $fallbackHash),
            'invoice_overdue' => 'invoice_overdue:'.($payload['invoice_id'] ?? $fallbackHash),
            'quote_sent' => 'quote_sent:'.($payload['estimate_id'] ?? $fallbackHash),
            'visit_tomorrow' => 'visit_tomorrow:'.($payload['job_id'] ?? $fallbackHash).':'.($payload['scheduled_date'] ?? $fallbackHash),
            default => $event.':'.$fallbackHash,
        };
    }

    private function resolveJob(array $payload): ?Job
    {
        if (! isset($payload['job_id'])) {
            return null;
        }

        return Job::query()->find((int) $payload['job_id']);
    }

    private function resolveInvoice(array $payload): ?Invoice
    {
        if (! isset($payload['invoice_id'])) {
            return null;
        }

        return Invoice::query()->find((int) $payload['invoice_id']);
    }

    private function resolveEstimate(array $payload): ?Estimate
    {
        if (! isset($payload['estimate_id'])) {
            return null;
        }

        return Estimate::query()->find((int) $payload['estimate_id']);
    }

    public function payloadFromCustomer(int $organizationId, int $customerId): array
    {
        $customer = Customer::query()
            ->where('organization_id', $organizationId)
            ->find($customerId);

        return [
            'organization_id' => $organizationId,
            'customer_id' => $customerId,
            'customer_email' => $customer?->email,
            'customer_phone' => $customer?->mobile ?: $customer?->phone,
        ];
    }
}
