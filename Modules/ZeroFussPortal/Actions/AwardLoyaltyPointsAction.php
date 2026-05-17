<?php

namespace Modules\ZeroFussPortal\Actions;

use Modules\ZeroFussPortal\Models\LoyaltyPoint;

class AwardLoyaltyPointsAction
{
    public function execute(
        int $companyId,
        int $customerId,
        int $points,
        string $reason,
        string $sourceType,
        int|string|null $sourceId = null,
        array $metadata = []
    ): LoyaltyPoint {
        return LoyaltyPoint::query()->firstOrCreate(
            [
                'company_id' => $companyId,
                'customer_id' => $customerId,
                'source_type' => $sourceType,
                'source_id' => $sourceId !== null ? (int) $sourceId : null,
                'direction' => 'earn',
            ],
            [
                'points' => max(0, $points),
                'reason' => $reason,
                'metadata' => $metadata,
                'awarded_at' => now(),
            ]
        );
    }
}
