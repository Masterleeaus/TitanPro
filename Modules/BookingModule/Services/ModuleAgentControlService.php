<?php

namespace Modules\BookingModule\Services;

class ModuleAgentControlService
{
    public function manifestPath(): string
    {
        return module_path('BookingModule', 'Agents/ModuleAgent/agent.manifest.json');
    }

    public function enabled(): bool
    {
        return file_exists($this->manifestPath());
    }
}
