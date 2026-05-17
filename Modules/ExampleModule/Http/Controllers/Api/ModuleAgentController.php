<?php

namespace Modules\ExampleModule\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ExampleModule\Services\ModuleAgentControlService;

class ModuleAgentController
{
    public function chat(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('example.agent.use') || abort(403);
        return $agent->ask($request->all());
    }

    public function command(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('example.agent.control') || abort(403);
        return $agent->command($request->all());
    }
}
