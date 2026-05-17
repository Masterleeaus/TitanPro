<?php

namespace Modules\Security\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Modules\Security\Contracts\Repositories\SecurityRepositoryInterface;
use Modules\Security\Entities\CardItems;
use Modules\Security\Entities\TrAccessCard;
use Modules\Security\Entities\TrInOutPermit;
use Modules\Security\Entities\WorkPermits;
use Modules\Security\Entities\WorkPermitsFile;
use Throwable;

class SecurityRepository implements SecurityRepositoryInterface
{
    /** @var array<string,class-string<Model>> */
    private array $models = [
        'goods_in_out_permits' => TrInOutPermit::class,
        'work_permits' => WorkPermits::class,
        'work_permit_files' => WorkPermitsFile::class,
        'access_cards' => TrAccessCard::class,
        'access_card_items' => CardItems::class,
    ];

    public function counts(): array
    {
        return $this->mapModels(fn (string $model): int => $this->safeCount($model));
    }

    public function pendingApprovals(): array
    {
        return [
            'goods_in_out_permits' => $this->safeWherePending(TrInOutPermit::class, 'status_approve'),
            'work_permits' => $this->safeWherePending(WorkPermits::class, 'status_approve'),
        ];
    }

    public function pendingValidations(): array
    {
        return [
            'goods_in_out_permits' => $this->safeWherePending(TrInOutPermit::class, 'status_validated'),
            'work_permits' => $this->safeWherePending(WorkPermits::class, 'status_validated'),
        ];
    }

    public function recent(int $limit = 10): array
    {
        $limit = max(1, min($limit, 50));

        return $this->mapModels(function (string $model) use ($limit): array {
            try {
                return $model::query()
                    ->latest('id')
                    ->limit($limit)
                    ->get()
                    ->map(fn (Model $record): array => [
                        'id' => $record->getKey(),
                        'created_at' => optional($record->getAttribute('created_at'))->toISOString(),
                        'updated_at' => optional($record->getAttribute('updated_at'))->toISOString(),
                    ])
                    ->all();
            } catch (Throwable) {
                return [];
            }
        });
    }

    private function mapModels(callable $callback): array
    {
        $out = [];
        foreach ($this->models as $key => $model) {
            $out[$key] = $callback($model);
        }
        return $out;
    }

    private function safeCount(string $model): int
    {
        try {
            return (int) $model::query()->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function safeWherePending(string $model, string $column): int
    {
        try {
            return (int) $model::query()->where(function ($query) use ($column) {
                $query->where($column, false)->orWhereNull($column);
            })->count();
        } catch (Throwable) {
            return 0;
        }
    }
}
