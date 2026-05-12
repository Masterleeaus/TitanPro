<?php

namespace Modules\ZeroFussPortal\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\ZeroFussPortal\Actions\CreateReferralAction;
use Modules\ZeroFussPortal\Models\Referral;

class ReferralService
{
    public function __construct(private readonly CreateReferralAction $createReferralAction)
    {
    }

    public function create(int $companyId, int $customerId, string $referredEmail, ?string $referredName = null, array $metadata = []): Referral
    {
        return $this->createReferralAction->execute($companyId, $customerId, $referredEmail, $referredName, $metadata);
    }

    public function listForCustomer(int $companyId, int $customerId): Collection
    {
        return Referral::query()
            ->where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
    }

    public function successfulCount(int $companyId, int $customerId): int
    {
        return Referral::query()
            ->where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->where('status', 'converted')
            ->count();
    }
}
