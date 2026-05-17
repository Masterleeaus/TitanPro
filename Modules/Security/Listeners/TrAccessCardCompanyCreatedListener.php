<?php

namespace Modules\Security\Listeners;
use App\Models\ModuleSetting;
use Modules\Security\Entities\TrAccessCard;

class TrAccessCardCompanyCreatedListener
{
    public function handle($event)
    {
        $company = $event->company;
        $roles = ['admin', 'employee', 'client'];
        ModuleSetting::createRoleSettingEntry('traccesscard', $roles, $company);
        // TrAccessCard::addModuleSetting($company);
    }
}
