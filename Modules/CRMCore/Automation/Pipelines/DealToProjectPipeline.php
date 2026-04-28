<?php

namespace Modules\CRMCore\Automation\Pipelines;

class DealToProjectPipeline
{
    public function steps(): array
    {
        return ['match_service', 'confirm_client', 'create_project', 'log_activity'];
    }
}
