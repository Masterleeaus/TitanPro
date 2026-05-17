<?php

namespace Modules\ZeroFussPortal\Actions;

use Illuminate\Support\Str;
use Modules\ZeroFussPortal\Events\ReferralCreated;
use Modules\ZeroFussPortal\Models\Referral;

class CreateReferralAction
{
    public function execute(int $companyId, int $customerId, string $referredEmail, ?string $referredName = null, array $metadata = []): Referral
    {
        $referral = Referral::query()->create([
            'company_id' => $companyId,
            'customer_id' => $customerId,
            'referral_code' => strtoupper(Str::random(10)),
            'referred_email' => trim(strtolower($referredEmail)),
            'referred_name' => $referredName,
            'status' => 'pending',
            'metadata' => $metadata,
        ]);

        ReferralCreated::dispatch($referral);

        return $referral;
    }
}
