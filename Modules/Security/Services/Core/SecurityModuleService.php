<?php

namespace Modules\Security\Services\Core;

use Illuminate\Support\Facades\Schema;
use Modules\Security\Contracts\Repositories\SecurityRepositoryInterface;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;
use Modules\Security\Entities\CardItems;
use Modules\Security\Entities\TrAccessCard;
use Modules\Security\Entities\TrInOutPermit;
use Modules\Security\Entities\WorkPermits;
use Modules\Security\Entities\WorkPermitsFile;
use Modules\Security\Support\DTOs\SecurityModuleStats;
use Modules\Security\Support\Diagnostics\SecurityModuleDiagnostic;
use Throwable;

class SecurityModuleService implements SecurityModuleServiceInterface
{
    public function __construct(
        private readonly SecurityRepositoryInterface $repository,
        private readonly SecurityModuleDiagnostic $diagnostic,
    ) {
    }

    public function dashboard(): array
    {
        return [
            'module' => 'security',
            'stats' => $this->stats()->toArray(),
            'pending' => [
                'approvals' => $this->repository->pendingApprovals(),
                'validations' => $this->repository->pendingValidations(),
            ],
            'recent' => $this->repository->recent(5),
            'features' => $this->features(),
        ];
    }

    public function health(): array
    {
        $tables = [
            'tr_in_out_permit',
            'tr_workpermits',
            'tr_workpermit_files',
            'tr_access_card',
            'tr_access_card_items',
        ];

        $tableStatus = [];
        foreach ($tables as $table) {
            try {
                $tableStatus[$table] = Schema::hasTable($table);
            } catch (Throwable) {
                $tableStatus[$table] = false;
            }
        }

        $ok = ! in_array(false, $tableStatus, true);

        return [
            'module' => 'security',
            'status' => $ok ? 'ok' : 'degraded',
            'tables' => $tableStatus,
            'features' => array_keys(array_filter($this->features())),
        ];
    }

    public function features(): array
    {
        return (array) config('security_features.features', config('security.features', []));
    }

    public function permissions(): array
    {
        return (array) config('security_permissions.permissions', config('security.permissions', []));
    }


    public function status(): array
    {
        $health = $this->health();

        return [
            'module' => 'security',
            'status' => $health['status'],
            'counts' => $this->repository->counts(),
            'pending_approvals' => array_sum($this->repository->pendingApprovals()),
            'pending_validations' => array_sum($this->repository->pendingValidations()),
        ];
    }

    public function diagnostics(): array
    {
        return $this->diagnostic->report();
    }

    public function stats(): SecurityModuleStats
    {
        return new SecurityModuleStats(
            goodsInOutPermits: $this->safeCount(TrInOutPermit::class),
            workPermits: $this->safeCount(WorkPermits::class),
            workPermitFiles: $this->safeCount(WorkPermitsFile::class),
            accessCards: $this->safeCount(TrAccessCard::class),
            accessCardItems: $this->safeCount(CardItems::class),
            pendingGoodsApprovals: $this->safeWhereFalse(TrInOutPermit::class, 'status_approve'),
            pendingWorkPermitApprovals: $this->safeWhereFalse(WorkPermits::class, 'status_approve'),
            pendingValidations: $this->safePendingValidations(),
        );
    }

    private function safeCount(string $model): int
    {
        try {
            return (int) $model::query()->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function safeWhereFalse(string $model, string $column): int
    {
        try {
            return (int) $model::query()->where(function ($query) use ($column) {
                $query->where($column, false)->orWhereNull($column);
            })->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function safePendingValidations(): int
    {
        try {
            $goods = TrInOutPermit::query()->where(function ($query) {
                $query->where('status_validated', false)->orWhereNull('status_validated');
            })->count();

            $work = WorkPermits::query()->where(function ($query) {
                $query->where('status_validated', false)->orWhereNull('status_validated');
            })->count();

            return (int) ($goods + $work);
        } catch (Throwable) {
            return 0;
        }
    }
}
