<?php

namespace Modules\ZeroFussPortal\Listeners;

use Modules\ZeroFussPortal\Services\LoyaltyService;

class AwardPointsOnPaymentListener
{
    public function __construct(private readonly LoyaltyService $loyaltyService)
    {
    }

    public function handle(mixed $eventPayload = null): void
    {
        $payload = is_array($eventPayload) ? $eventPayload : (array) $eventPayload;

        $companyId = (int) ($payload['company_id'] ?? 0);
        $customerId = (int) ($payload['customer_id'] ?? 0);
        $invoiceId = (int) ($payload['invoice_id'] ?? 0);
        $points = isset($payload['points']) ? (int) $payload['points'] : null;

        if ($companyId <= 0 || $customerId <= 0 || $invoiceId <= 0) {
            return;
        }

        $this->loyaltyService->awardForInvoicePayment(
            companyId: $companyId,
            customerId: $customerId,
            invoiceId: $invoiceId,
            points: $points
        );
    }
}
