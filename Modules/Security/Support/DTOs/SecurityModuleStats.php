<?php

namespace Modules\Security\Support\DTOs;

final class SecurityModuleStats
{
    public function __construct(
        public readonly int $goodsInOutPermits = 0,
        public readonly int $workPermits = 0,
        public readonly int $workPermitFiles = 0,
        public readonly int $accessCards = 0,
        public readonly int $accessCardItems = 0,
        public readonly int $pendingGoodsApprovals = 0,
        public readonly int $pendingWorkPermitApprovals = 0,
        public readonly int $pendingValidations = 0,
    ) {
    }

    public function toArray(): array
    {
        return [
            'goods_in_out_permits' => $this->goodsInOutPermits,
            'work_permits' => $this->workPermits,
            'work_permit_files' => $this->workPermitFiles,
            'access_cards' => $this->accessCards,
            'access_card_items' => $this->accessCardItems,
            'pending_goods_approvals' => $this->pendingGoodsApprovals,
            'pending_work_permit_approvals' => $this->pendingWorkPermitApprovals,
            'pending_validations' => $this->pendingValidations,
        ];
    }
}
