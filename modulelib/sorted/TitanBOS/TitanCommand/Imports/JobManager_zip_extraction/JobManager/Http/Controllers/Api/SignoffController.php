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

class SignoffController extends Controller
{
    public function pdf(Request $request)
    {
        $payload = $request->only(['job_id','title','client_name','signature','notes']);
        if (empty($payload['title'])) $payload['title'] = 'Job Sign-off';
        if (empty($payload['notes'])) $payload['notes'] = 'Client approved job completion.';

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
