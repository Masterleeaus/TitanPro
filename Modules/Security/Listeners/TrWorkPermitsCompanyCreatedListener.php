<?php

namespace Modules\Security\Listeners;
use App\Models\ModuleSetting;
use Modules\Security\Entities\WorkPermits;

class TrWorkPermitsCompanyCreatedListener
{
    public function handle($event)
    {
        $company = $event->company;
        $roles = ['admin', 'employee', 'client'];
        ModuleSetting::createRoleSettingEntry('trworkpermits', $roles, $company);
        // WorkPermits::addModuleSetting($company);
    }
}
