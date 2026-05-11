<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;






class AIAssistantProxyController extends Controller
{
    public function chat(Request $request)
    {
        abort_unless(env('AICOPILOT_ENABLED', true), 403, 'AICopilot disabled');

        $url = rtrim(env('AICOPILOT_BASE_URL'), '/') . '/api/aicopilot/assistant';
        $payload = $request->only(['assistant', 'message', 'tenant_system_append']);
        if (!isset($payload['assistant'])) {
            $payload['assistant'] = env('AICOPILOT_ASSISTANT', 'main');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AICOPILOT_API_KEY'),
            'Accept' => 'application/json',
        ])->timeout(env('AICOPILOT_TIMEOUT', 30))
          ->post($url, $payload);

        $json = $response->json();
        logger()->info('JobManager.AI.assistant', ['payload'=>$payload, 'status'=>$response->status()]);
        return response()->json($json, $response->status());
    }
}
