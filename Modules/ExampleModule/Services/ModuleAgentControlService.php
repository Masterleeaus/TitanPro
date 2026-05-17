<?php

namespace Modules\ExampleModule\Services;

class ModuleAgentControlService
{
    public function ask(array $payload): array
    {
        return app('titan-agents')->forModule('example-module')->ask($payload);
    }

    public function command(array $payload): array
    {
        return app('titan-agents')->forModule('example-module')->command($payload);
    }
}
