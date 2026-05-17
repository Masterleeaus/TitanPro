<?php

namespace Modules\ZeroPayModule\ValueObjects;

use Modules\ZeroPayModule\Models\ZeroPaySession;

/**
 * Result returned by the bank transfer matcher.
 *
 * - status: auto_matched|needs_review
 * - session is null when no candidate session was identified
 * - matchedCriteria includes boolean keys: reference, amount, customer, timestamp
 */
class MatchResult
{
    /** @param array<string,bool> $matchedCriteria */
    public function __construct(
        /** True when all automatic criteria were satisfied and applied. */
        public bool $matched,
        /** Matched session; null if no suitable session candidate was found. */
        public ?ZeroPaySession $session,
        /** Confidence score between 0.0 and 1.0. */
        public float $confidence,
        /** Per-criterion pass/fail map with keys reference|amount|customer|timestamp. */
        public array $matchedCriteria,
        /** Matching outcome for automation decisions (auto_matched|needs_review). */
        public string $status,
    ) {}
}
