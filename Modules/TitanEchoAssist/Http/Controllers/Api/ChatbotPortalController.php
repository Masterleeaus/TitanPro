<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;
use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;

class ChatbotPortalController extends Controller
{
    public function __construct(private readonly ChatbotPortalWidgetMenuService $menuService) {}

    public function menu(string $uuid, string $sessionId): JsonResponse
    {
        [$chatbot, $chatbotCustomer, $customer] = $this->resolveContext($uuid, $sessionId);
        $nextVisit = $this->nextVisit($chatbot, $customer);
        $lastCompletedJob = $this->lastCompletedJob($chatbot, $customer);
        $outstandingInvoices = $this->outstandingInvoicesCount($chatbot, $customer);
        $pendingQuotes = $this->pendingQuotesCount($chatbot, $customer);

        return response()->json($this->menuService->build([
            'first_name' => $this->firstName($customer, $chatbotCustomer),
            'customer_name' => $this->fullName($customer, $chatbotCustomer),
            'customer_email' => (string) ($customer?->email ?: $chatbotCustomer->email ?: ''),
            'properties' => $this->customerProperties($customer),
            'next_visit_label' => $nextVisit?->scheduled_at?->format('l j M') ?? "You're all caught up for now",
            'show_pay_invoice' => $outstandingInvoices > 0,
            'show_approve_quote' => $pendingQuotes > 0,
            'show_request_reclean' => $lastCompletedJob?->completed_at?->greaterThanOrEqualTo(now()->subDays(7)) ?? false,
            'show_rate_visit' => $lastCompletedJob !== null && $lastCompletedJob->quality_score === null,
        ]));
    }

    public function home(string $uuid, string $sessionId): JsonResponse
    {
        [$chatbot, $chatbotCustomer, $customer] = $this->resolveContext($uuid, $sessionId);
        $nextVisit = $this->nextVisit($chatbot, $customer);
        $lastCompletedJob = $this->lastCompletedJob($chatbot, $customer);
        $outstandingInvoices = $this->outstandingInvoicesCount($chatbot, $customer);
        $pendingQuotes = $this->pendingQuotesCount($chatbot, $customer);

        return response()->json([
            'upcoming_visits' => $this->upcomingVisitsCount($chatbot, $customer),
            'outstanding_invoices' => $outstandingInvoices,
            'unread_notifications' => $this->unreadNotificationsCount($chatbot, $chatbotCustomer),
            'next_visit' => $nextVisit ? [
                'date' => $nextVisit->scheduled_at?->toDateString(),
                'time' => $nextVisit->scheduled_at?->format('H:i'),
                'type' => $nextVisit->title,
            ] : null,
            'menu' => $this->menuService->build([
                'first_name' => $this->firstName($customer, $chatbotCustomer),
                'customer_name' => $this->fullName($customer, $chatbotCustomer),
                'customer_email' => (string) ($customer?->email ?: $chatbotCustomer->email ?: ''),
                'properties' => $this->customerProperties($customer),
                'next_visit_label' => $nextVisit?->scheduled_at?->format('l j M') ?? "You're all caught up for now",
                'show_pay_invoice' => $outstandingInvoices > 0,
                'show_approve_quote' => $pendingQuotes > 0,
                'show_request_reclean' => $lastCompletedJob?->completed_at?->greaterThanOrEqualTo(now()->subDays(7)) ?? false,
                'show_rate_visit' => $lastCompletedJob !== null && $lastCompletedJob->quality_score === null,
            ]),
        ]);
    }

    private function resolveContext(string $uuid, string $sessionId): array
    {
        $chatbot = Chatbot::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $chatbotCustomer = ChatbotCustomer::query()
            ->where('chatbot_id', $chatbot->id)
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $customer = $this->resolveCustomer($chatbot, $chatbotCustomer);

        return [$chatbot, $chatbotCustomer, $customer];
    }

    private function resolveCustomer(Chatbot $chatbot, ChatbotCustomer $chatbotCustomer): ?Customer
    {
        $email = trim((string) ($chatbotCustomer->email ?? ''));
        $phone = trim((string) ($chatbotCustomer->phone ?? ''));

        if ($email === '' && $phone === '') {
            return null;
        }

        return Customer::query()
            ->where('organization_id', $chatbot->company_id)
            ->where(function ($query) use ($email, $phone): void {
                if ($email !== '') {
                    $query->where('email', $email);

                    if ($phone !== '') {
                        $query->orWhere('phone', $phone)->orWhere('mobile', $phone);
                    }

                    return;
                }

                $query->where('phone', $phone)->orWhere('mobile', $phone);
            })
            ->first();
    }

    private function nextVisit(Chatbot $chatbot, ?Customer $customer): ?Job
    {
        if ($customer === null) {
            return null;
        }

        return Job::query()
            ->where('organization_id', $chatbot->company_id)
            ->where('customer_id', $customer->id)
            ->where('status', Job::STATUS_SCHEDULED)
            ->whereNull('completed_at')
            ->whereNotNull('scheduled_at')
            ->whereDate('scheduled_at', '>=', now()->toDateString())
            ->orderBy('scheduled_at')
            ->first();
    }

    private function upcomingVisitsCount(Chatbot $chatbot, ?Customer $customer): int
    {
        if ($customer === null) {
            return 0;
        }

        return Job::query()
            ->where('organization_id', $chatbot->company_id)
            ->where('customer_id', $customer->id)
            ->where('status', Job::STATUS_SCHEDULED)
            ->whereNull('completed_at')
            ->whereNotNull('scheduled_at')
            ->whereDate('scheduled_at', '>=', now()->toDateString())
            ->count();
    }

    private function outstandingInvoicesCount(Chatbot $chatbot, ?Customer $customer): int
    {
        if ($customer === null) {
            return 0;
        }

        return Invoice::query()
            ->where('organization_id', $chatbot->company_id)
            ->where('customer_id', $customer->id)
            ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
            ->where('balance_due', '>', 0)
            ->count();
    }

    private function pendingQuotesCount(Chatbot $chatbot, ?Customer $customer): int
    {
        if ($customer === null) {
            return 0;
        }

        return Estimate::query()
            ->where('organization_id', $chatbot->company_id)
            ->where('customer_id', $customer->id)
            ->where('status', Estimate::STATUS_SENT)
            ->count();
    }

    private function lastCompletedJob(Chatbot $chatbot, ?Customer $customer): ?Job
    {
        if ($customer === null) {
            return null;
        }

        return Job::query()
            ->where('organization_id', $chatbot->company_id)
            ->where('customer_id', $customer->id)
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->first();
    }

    private function unreadNotificationsCount(Chatbot $chatbot, ChatbotCustomer $chatbotCustomer): int
    {
        if (!Schema::hasTable('ext_chatbot_portal_notifications')) {
            return 0;
        }

        $query = DB::table('ext_chatbot_portal_notifications');

        if (Schema::hasColumn('ext_chatbot_portal_notifications', 'company_id')) {
            $query->where('company_id', $chatbot->company_id);
        }

        if (Schema::hasColumn('ext_chatbot_portal_notifications', 'chatbot_id')) {
            $query->where('chatbot_id', $chatbot->id);
        }

        if (Schema::hasColumn('ext_chatbot_portal_notifications', 'chatbot_customer_id')) {
            $query->where('chatbot_customer_id', $chatbotCustomer->id);
        } elseif (Schema::hasColumn('ext_chatbot_portal_notifications', 'session_id')) {
            $query->where('session_id', $chatbotCustomer->session_id);
        }

        if (Schema::hasColumn('ext_chatbot_portal_notifications', 'read_at')) {
            $query->whereNull('read_at');
        } elseif (Schema::hasColumn('ext_chatbot_portal_notifications', 'is_read')) {
            $query->where('is_read', false);
        }

        return $query->count();
    }

    private function customerProperties(?Customer $customer): array
    {
        if ($customer === null) {
            return [];
        }

        return $customer->properties()
            ->get(['id', 'name', 'address_line1', 'city', 'state', 'postal_code'])
            ->map(fn ($property): array => [
                'id' => $property->id,
                'name' => $property->name ?: 'Property',
                'address' => implode(', ', array_filter([
                    $property->address_line1,
                    $property->city,
                    $property->state,
                    $property->postal_code,
                ])),
            ])
            ->values()
            ->all();
    }

    private function firstName(?Customer $customer, ChatbotCustomer $chatbotCustomer): string
    {
        if ($customer?->first_name) {
            return $customer->first_name;
        }

        $name = trim((string) $chatbotCustomer->name);

        return $name !== '' ? explode(' ', $name)[0] : 'there';
    }

    private function fullName(?Customer $customer, ChatbotCustomer $chatbotCustomer): string
    {
        if ($customer !== null) {
            return trim($customer->full_name);
        }

        return trim((string) $chatbotCustomer->name) ?: 'Customer';
    }
}
