<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;






class AIComplianceProxyController extends Controller
{
    public function pdf(Request $request)
    {
        abort_unless(env('AICOPILOT_ENABLED', true), 403, 'AICopilot disabled');

        $url = rtrim(env('AICOPILOT_BASE_URL'), '/') . '/api/aicopilot/compliance/pdf';
        $payload = $request->only(['job_id', 'title', 'checklist', 'notes']);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AICOPILOT_API_KEY'),
            'Accept' => 'application/json',
        ])->timeout(env('AICOPILOT_TIMEOUT', 30))
          ->post($url, $payload);

        $json = $response->json();
        if (isset($json['file_url'])) {
            logger()->info('AICopilot PDF', ['file_url'=>$json['file_url'], 'endpoint'=>request()->path()]);
        }
        logger()->info('JobManager.AI.compliance', ['payload'=>$payload, 'status'=>$response->status()]);
        return response()->json($json, $response->status());
    }
}
