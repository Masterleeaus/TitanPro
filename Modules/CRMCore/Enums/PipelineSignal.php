<?php

namespace Modules\CRMCore\Enums;

enum PipelineSignal: string
{
    case LeadScored = 'crmcore.lead.scored';
    case DealReadyForProject = 'crmcore.deal.ready_for_project';
    case DealConvertedToProject = 'crmcore.deal.converted_to_project';
}
