<?php

namespace Modules\TitanNexus\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\TitanNexus\Services\ModuleAgentControlService;

class ModuleAgentController
{
    public function chat(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('titan_nexus.agent.use') || abort(403);
        return $agent->ask($request->all());
    }

    public function command(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('titan_nexus.agent.control') || abort(403);
        return $agent->command($request->all());
    }
}
