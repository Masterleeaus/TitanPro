<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Http\Controllers\Api\Concerns\ResolvesPortalContext;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;
use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanEchoAssist\Services\WorkcoreSchedulingDataService;

class PortalHomeController extends Controller
{
    use ResolvesPortalContext;

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
                'next_visit_label' => $this->nextVisitLabel($nextVisit),
                'show_pay_invoice' => $outstandingBalance > 0,
                'show_approve_quote' => $pendingQuotes > 0,
                'show_request_reclean' => false,
                'show_rate_visit' => false,
            ]),
        ]);
    }

    private function firstName(?Customer $customer, ChatbotCustomer $chatbotCustomer): string
    {
        if ($customer?->first_name) {
            return $customer->first_name;
        }

        $name = trim((string) $chatbotCustomer->name);
        if ($name === '') {
            return 'there';
        }

        $parts = preg_split('/\s+/', $name);
        if (! is_array($parts) || $parts === []) {
            return $name;
        }

        return $parts[0];
    }

    private function fullName(?Customer $customer, ChatbotCustomer $chatbotCustomer): string
    {
        if ($customer !== null) {
            return trim($customer->full_name);
        }

        return trim((string) $chatbotCustomer->name) ?: 'Customer';
    }

    private function nextVisitLabel(?array $nextVisit): string
    {
        if ($nextVisit === null) {
            return "You're all caught up for now";
        }

        $scheduledAt = (string) ($nextVisit['scheduled_at'] ?? '');
        if ($scheduledAt === '') {
            return 'Visit scheduled';
        }

        try {
            return \Illuminate\Support\Carbon::parse($scheduledAt)->format('l j M');
        } catch (\Throwable) {
            return $scheduledAt;
        }
    }
}
