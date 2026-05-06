<?php

namespace Modules\TitanNexus\Services;

class ModuleAgentControlService
{
    public function ask(array $payload): array
    {
        return app('titan-agents')->forModule('titan-nexus')->ask($payload);
    }

    public function command(array $payload): array
    {
        return app('titan-agents')->forModule('titan-nexus')->command($payload);
    }
}
