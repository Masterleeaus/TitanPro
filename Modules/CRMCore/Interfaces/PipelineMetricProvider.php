<?php

namespace Modules\CRMCore\Interfaces;

interface PipelineMetricProvider
{
    public function metrics(): array;
}
