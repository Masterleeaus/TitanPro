<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;
use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanEchoAssist\Support\WorkcoreSchemaMap;

class PortalWorkDataController extends Controller
{
    public function __construct(private readonly WorkcorePortalDataService $workcore) {}

    public function __invoke(string $uuid, string $sessionId): JsonResponse
    {
        [$chatbot, $customer] = $this->resolveContext($uuid, $sessionId);
        if (! $customer) {
            return response()->json([
                'upcoming_visits' => [],
                'past_visits' => [],
                'invoices' => [],
                'quotes' => [],
                'documents' => [],
                'issues' => [],
            ]);
        }

        $customerId = (int) $customer->id;
        $companyId = (int) $chatbot->company_id;

        return response()->json([
            'upcoming_visits' => $this->workcore->getUpcomingVisits($customerId, $companyId),
            'past_visits' => $this->workcore->getPastVisits($customerId, $companyId),
            'invoices' => $this->workcore->getInvoices($customerId, $companyId),
            'quotes' => $this->workcore->getQuotes($customerId, $companyId),
            'documents' => $this->documents($customerId, $companyId),
            'issues' => $this->workcore->getServiceIssues($customerId, $companyId),
        ]);
    }

    private function documents(int $customerId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::DOCUMENT_LINKS_TABLE)) {
            return [];
        }

        return DB::table(WorkcoreSchemaMap::DOCUMENT_LINKS_TABLE)
            ->where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->get(['id', 'document_type', 'title', 'file_path', 'external_url', 'expires_at', 'is_signed', 'signed_at'])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    private function resolveContext(string $uuid, string $sessionId): array
    {
        $chatbot = Chatbot::query()->where('uuid', $uuid)->firstOrFail();
        $chatbotCustomer = ChatbotCustomer::query()
            ->where('chatbot_id', $chatbot->id)
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $email = trim((string) ($chatbotCustomer->email ?? ''));
        $phone = trim((string) ($chatbotCustomer->phone ?? ''));
        if ($email === '' && $phone === '') {
            return [$chatbot, null];
        }

        $customer = Customer::query()
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

        return [$chatbot, $customer];
    }
}
