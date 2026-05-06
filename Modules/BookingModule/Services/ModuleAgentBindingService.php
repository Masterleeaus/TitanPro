<?php

namespace Modules\BookingModule\Services;

class ModuleAgentBindingService
{
    public function bindings(): array
    {
        return ['agent' => 'BookingModuleAgent', 'module' => 'bookingmodule'];
    }
}
