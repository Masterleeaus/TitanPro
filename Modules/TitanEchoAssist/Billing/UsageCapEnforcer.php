<?php

namespace Modules\TitanEchoAssist\Billing;

use Illuminate\Support\Facades\Log;
use Modules\TitanEchoAssist\Billing\Limits\ConversationLimit;

/**
 * Enforces per-company API usage caps before allowing chargeable AI calls.
 *
 * Blueprint 22: all cap decisions are scoped by company_id.
 */
class UsageCapEnforcer
{
    public function __construct(
        private readonly ConversationLimit $conversationLimit,
    ) {}

    /**
     * Assert that the company has not exceeded its conversation cap.
     * Throws CapExceededException if the limit is reached.
     *
     * @throws CapExceededException
     */
    public function assertAllowed(int $companyId, string $channel = 'website'): void
    {
        $usage = $this->conversationLimit->getUsage($companyId);

        if ($usage >= $this->conversationLimit->getCap()) {
            Log::warning('UsageCapEnforcer: conversation cap exceeded', [
                'company_id' => $companyId,
                'channel'    => $channel,
                'cap'        => $this->conversationLimit->getCap(),
                'usage'      => $usage,
            ]);

            throw new CapExceededException(
                "Conversation cap exceeded for company {$companyId}.",
                $companyId,
            );
        }
    }

    /**
     * Check whether a chargeable action is allowed for the company without throwing.
     */
    public function isAllowed(int $companyId): bool
    {
        return ! $this->conversationLimit->isExceeded($companyId);
    }

    /**
     * Record a chargeable conversation against the company's usage counter.
     */
    public function record(int $companyId): int
    {
        return $this->conversationLimit->increment($companyId);
    }
}
