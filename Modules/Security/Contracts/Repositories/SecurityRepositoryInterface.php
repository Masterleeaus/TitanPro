<?php

namespace Modules\Security\Contracts\Repositories;

interface SecurityRepositoryInterface
{
    public function counts(): array;

    public function pendingApprovals(): array;

    public function pendingValidations(): array;

    public function recent(int $limit = 10): array;
}
