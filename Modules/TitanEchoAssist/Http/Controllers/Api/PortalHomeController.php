<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;
use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanEchoAssist\Services\WorkcoreSchedulingDataService;

class PortalHomeController extends Controller
{
    public function __construct(
        private readonly ChatbotPortalWidgetMenuService $menuService,
        private readonly WorkcorePortalDataService $workcore,
        private readonly WorkcoreSchedulingDataService $scheduling,
    ) {}

    public function __invoke(string $uuid, string $sessionId): JsonResponse
    {
        [$chatbot, $chatbotCustomer, $customer] = $this->resolveContext($uuid, $sessionId);
        $companyId = (int) $chatbot->company_id;
        $customerId = (int) ($customer->id ?? 0);
        $nextVisit = $customer ? $this->scheduling->getNextVisit($customerId, $companyId) : null;
        $visitCount = $customer ? $this->scheduling->getVisitCountThisMonth($customerId, $companyId) : 0;
        $outstandingBalance = $customer ? $this->workcore->getOutstandingBalance($customerId, $companyId) : 0.0;
        $pendingQuotes = $customer ? count($this->workcore->getQuotes($customerId, $companyId, 'sent')) : 0;

        return response()->json([
            'upcoming_visits' => $visitCount,
            'outstanding_invoices' => $customer ? count($this->workcore->getInvoices($customerId, $companyId)) : 0,
            'outstanding_balance' => $outstandingBalance,
            'next_visit' => $nextVisit ? [
                'date' => isset($nextVisit['scheduled_at']) ? (string) $nextVisit['scheduled_at'] : null,
                'type' => $nextVisit['title'] ?? null,
                'status' => $nextVisit['status'] ?? null,
            ] : null,
            'menu' => $this->menuService->build([
                'first_name' => $this->firstName($customer, $chatbotCustomer),
                'customer_name' => $this->fullName($customer, $chatbotCustomer),
                'customer_email' => (string) ($customer?->email ?: $chatbotCustomer->email ?: ''),
                'properties' => $customer ? ($this->workcore->getCustomerProfile((int) $customer->id, $companyId)['properties'] ?? []) : [],
                'next_visit_label' => $nextVisit['scheduled_at'] ?? "You're all caught up for now",
                'show_pay_invoice' => $outstandingBalance > 0,
                'show_approve_quote' => $pendingQuotes > 0,
                'show_request_reclean' => false,
                'show_rate_visit' => false,
            ]),
        ]);
    }

    private function resolveContext(string $uuid, string $sessionId): array
    {
        $chatbot = Chatbot::query()->where('uuid', $uuid)->firstOrFail();
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
