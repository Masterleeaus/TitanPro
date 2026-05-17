<?php

namespace Modules\ZeroPayModule\Services;

class ModuleAgentControlService
{
    public function ask(array $payload): array
    {
        return app('titan-agents')->forModule('zeropay-module')->ask($payload);
    }

    public function command(array $payload): array
    {
        return app('titan-agents')->forModule('zeropay-module')->command($payload);
    }
}
