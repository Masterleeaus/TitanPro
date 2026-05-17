<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Governance;

class BosApprovalValidator
{
    public function validate(array $governed): array
    {
        $zeroApproved = ($governed['zero_approval'] ?? null) === 'approved';
        $bosApproved = ($governed['bos_approval'] ?? null) === 'approved';

        return [
            'zero_approved' => $zeroApproved,
            'bos_approved' => $bosApproved,
            'dual_approval' => $zeroApproved && $bosApproved,
            'status' => $zeroApproved && $bosApproved ? 'processed' : 'processing',
        ];
    }
}
