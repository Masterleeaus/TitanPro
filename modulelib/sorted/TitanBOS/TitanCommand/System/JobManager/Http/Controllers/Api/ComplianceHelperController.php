<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;






class ComplianceHelperController extends Controller
{
    public function generateSWMS(Request $request)
    {
        $payload = $request->all();
        if (empty($payload['checklist'])) {
            $path = base_path('Modules/JobManager/Resources/checklists/default.json');
            if (file_exists($path)) {
                $json = json_decode(file_get_contents($path), true);
                if (!empty($json['items'])) $payload['checklist'] = $json['items'];
                if (!empty($json['title']) && empty($payload['title'])) $payload['title'] = $json['title'];
            }
        }

        $url = rtrim(env('AICOPILOT_BASE_URL'), '/') . '/api/aicopilot/compliance/pdf';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AICOPILOT_API_KEY'),
            'Accept' => 'application/json',
        ])->timeout(env('AICOPILOT_TIMEOUT', 30))
          ->post($url, $payload);

        $json = $response->json();
        if (isset($json['file_url'])) {
            logger()->info('AICopilot PDF', ['file_url'=>$json['file_url'], 'endpoint'=>request()->path()]);
        }
        return response()->json($json, $response->status());
    }
}
