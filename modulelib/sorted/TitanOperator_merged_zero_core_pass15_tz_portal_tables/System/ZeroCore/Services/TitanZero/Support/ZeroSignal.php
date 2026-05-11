<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class ZeroSignal
{
    public static function stageModel(): array
    {
        return config('titan_operator.zero.signal_stage_model', []);
    }

    public static function initialProposalStatus(): string
    {
        return (string) data_get(static::stageModel(), 'proposal_statuses.initial', 'process');
    }

    public static function normalizeReviewStatus(string $status): string
    {
        $status = strtolower(trim($status));

        return match ($status) {
            'approve', 'approved' => 'approved',
            'reject', 'rejected' => 'rejected',
            'processing' => 'processing',
            'processed', 'complete', 'completed' => 'processed',
            default => 'pending_review',
        };
    }

    public static function stageForStatus(string $status): string
    {
        return match (static::normalizeReviewStatus($status)) {
            'approved' => 'zero_approved',
            'processing' => 'processing',
            'processed' => 'processed',
            'rejected' => 'rejected',
            default => 'process',
        };
    }

    public static function auditEventForStatus(string $status): string
    {
        return 'zero.signal.' . static::stageForStatus($status);
    }
}
