<?php

namespace Modules\JobManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;


namespace ModulesJobManagerHttpControllersApi;


namespace Modules\JobManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class AIToolProxyController extends Controller
{
    public function run(Request $request)
    {
        abort_unless(env('AICOPILOT_ENABLED', true), 403, 'AICopilot disabled');

        $url = rtrim(env('AICOPILOT_BASE_URL'), '/') . '/api/aicopilot/tool';
        $payload = $request->only(['tool', 'payload']);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AICOPILOT_API_KEY'),
            'Accept' => 'application/json',
        ])->timeout(env('AICOPILOT_TIMEOUT', 30))
          ->post($url, $payload);

        logger()->info('JobManager.AI.tool', ['payload'=>$payload, 'status'=>$response->status()]);
        return response()->json($response->json(), $response->status());
    }
}
