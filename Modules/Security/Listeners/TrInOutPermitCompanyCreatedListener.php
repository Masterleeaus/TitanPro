<?php

namespace Modules\Security\Listeners;
use App\Models\ModuleSetting;
use Modules\Security\Entities\TrInOutPermit;

class TrInOutPermitCompanyCreatedListener
{
    public function handle($event)
    {
        $company = $event->company;
        $roles = ['admin', 'employee', 'client'];
        ModuleSetting::createRoleSettingEntry('trinoutpermit', $roles, $company);
        // TrInOutPermit::addModuleSetting($company);
    }
}
