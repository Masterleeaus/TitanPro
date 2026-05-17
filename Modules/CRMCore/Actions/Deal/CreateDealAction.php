<?php

namespace Modules\CRMCore\Actions\Deal;

use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealPipeline;
use Modules\CRMCore\Models\DealStage;
use Modules\CRMCore\Scopes\ScopedByCompany;

class CreateDealAction
{
    /**
     * Create a new tenant-scoped deal.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): Deal
    {
        $companyId = $data['company_id'] ?? ScopedByCompany::resolveCompanyId();

        if (! is_numeric($companyId)) {
            throw new \RuntimeException('company_id is required to create a deal.');
        }

        $data['company_id'] = (int) $companyId;
        $data['pipeline_id'] ??= $this->defaultPipelineId((int) $data['company_id']);
        $data['deal_stage_id'] ??= $this->defaultStageId((int) $data['pipeline_id']);
        $data['currency'] ??= 'USD';

        return Deal::create($data);
    }

    private function defaultPipelineId(int $companyId): ?int
    {
        return DealPipeline::query()
            ->where('is_default', true)
            ->value('id')
            ?? DealPipeline::query()
                ->orderBy('position')
                ->value('id');
    }

    private function defaultStageId(?int $pipelineId): ?int
    {
        if ($pipelineId === null) {
            return null;
        }

        return DealStage::query()
            ->where('pipeline_id', $pipelineId)
            ->where('is_default_for_pipeline', true)
            ->value('id')
            ?? DealStage::query()
                ->where('pipeline_id', $pipelineId)
                ->orderBy('position')
                ->value('id');
    }
}
