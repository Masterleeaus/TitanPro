<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanEchoAssist\Services\ModuleAgentControlService;

class ModuleAgentController extends Controller
{
    public function __construct(private readonly ModuleAgentControlService $agent) {}

    public function tools(): mixed
    {
        $payload = ['tools' => $this->agent->tools()];
        return function_exists('response') ? response()->json($payload) : $payload;
    }

    public function invoke(Request $request, string $tool): mixed
    {
        $payload = $this->agent->invoke($tool, (array) $request->all());
        return function_exists('response') ? response()->json(['ok' => true, 'result' => $payload]) : $payload;
    }
}
