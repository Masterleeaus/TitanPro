<?php

namespace Modules\CRMCore\Workflows\Definitions;

class DealToProjectWorkflow
{
    public static function key(): string
    {
        return 'crmcore.deal_to_project';
    }

    public static function stages(): array
    {
        return ['deal_identified', 'service_matched', 'client_confirmed', 'project_created'];
    }
}
