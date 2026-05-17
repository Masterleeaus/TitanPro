<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Concerns;

use App\Models\Customer;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Models\ChatbotCustomer;

trait ResolvesPortalContext
{
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

        if ($email !== '') {
            $matchedByEmail = Customer::query()
                ->where('organization_id', $chatbot->company_id)
                ->where('email', $email)
                ->first();

            if ($matchedByEmail !== null || $phone === '') {
                return $matchedByEmail;
            }
        }

        return Customer::query()
            ->where('organization_id', $chatbot->company_id)
            ->where(function ($query) use ($phone): void {
                $query->where('phone', $phone)->orWhere('mobile', $phone);
            })
            ->first();
    }
}
