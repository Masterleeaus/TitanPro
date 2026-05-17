<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\Variance;

use Modules\Budgeting\Contracts\Services\VarianceServiceContract;
use Modules\Budgeting\Events\Domain\VarianceDetected;
use Modules\Budgeting\Models\BudgetActual;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceService implements VarianceServiceContract
{
    public function __construct(protected BudgetVariance $model) {}

    public function calculate(BudgetActual $actual): BudgetVariance
    {
        $varianceAmount = $actual->actual_amount - $actual->planned_amount;
        $variancePct = $actual->planned_amount > 0
            ? ($varianceAmount / $actual->planned_amount) * 100
            : 0;

        $flag = $this->resolveFlag(abs((float) $variancePct));

        $variance = $this->model->newQuery()->updateOrCreate(
            ['actual_id' => $actual->id],
            [
                'company_id' => $actual->company_id,
                'variance_amount' => $varianceAmount,
                'variance_pct' => $variancePct,
                'flag' => $flag,
            ]
        );

        if ($flag !== 'normal') {
            event(new VarianceDetected($variance));
        }

        return $variance;
    }

    public function flagAnomalies(int $companyId): int
    {
        $warningPct = (float) config('actuals.variance_warning_pct', 10.0);
        $criticalPct = (float) config('actuals.variance_critical_pct', 25.0);

        $count = $this->model->newQuery()
            ->where('company_id', $companyId)
            ->where('anomaly_flagged', false)
            ->where(fn ($q) => $q
                ->whereRaw('ABS(variance_pct) >= ?', [$criticalPct])
                ->orWhereRaw('ABS(variance_pct) >= ?', [$warningPct])
            )
            ->update(['anomaly_flagged' => true]);

        return $count;
    }

    public function getReport(int $companyId, array $filters = []): array
    {
        $query = $this->model->newQuery()
            ->where('company_id', $companyId)
            ->with('actual');

        if (isset($filters['flag'])) {
            $query->where('flag', $filters['flag']);
        }

        if (isset($filters['anomaly_only']) && $filters['anomaly_only']) {
            $query->where('anomaly_flagged', true);
        }

        return $query->get()->toArray();
    }

    private function resolveFlag(float $absPct): string
    {
        $criticalPct = (float) config('actuals.variance_critical_pct', 25.0);
        $warningPct = (float) config('actuals.variance_warning_pct', 10.0);

        if ($absPct >= $criticalPct) {
            return 'critical';
        }

        if ($absPct >= $warningPct) {
            return 'warning';
        }

        return 'normal';
    }
}
