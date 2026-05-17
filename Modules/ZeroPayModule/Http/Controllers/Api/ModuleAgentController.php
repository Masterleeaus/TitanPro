<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ZeroPayModule\Services\ModuleAgentControlService;

class ModuleAgentController
{
    public function chat(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('zeropay.agent.use') || abort(403);

        return $agent->ask($request->all());
    }

    public function command(Request $request, ModuleAgentControlService $agent): array
    {
        $request->user()?->can('zeropay.agent.control') || abort(403);

        return $agent->command($request->all());
    }
}
