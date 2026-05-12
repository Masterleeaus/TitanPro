<?php

namespace Modules\CRMCore\Enums;

enum PipelineSignal: string
{
    case ContactCreated = 'crmcore.contact.created';
    case DealWon = 'crmcore.deal.won';
    case DealLost = 'crmcore.deal.lost';
    case ActivityLogged = 'crmcore.activity.logged';
    case LeadScored = 'crmcore.lead.scored';
    case DealReadyForProject = 'crmcore.deal.ready_for_project';
    case DealConvertedToProject = 'crmcore.deal.converted_to_project';
}
