<?php

namespace Modules\CRMCore\Enums;

enum PipelineSignal: string
{
    case LeadScored = 'lead_scored';
    case DealReadyForProject = 'deal_ready_for_project';
    case DealConvertedToProject = 'deal_converted_to_project';
}
