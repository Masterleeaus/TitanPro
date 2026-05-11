<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    public function index()
    {
        $checks = [
            'routes_loaded' => true,
            'twilio_account_sid_set' => (bool) config('titantalk.twilio.account_sid'),
            'twilio_auth_token_set' => (bool) config('titantalk.twilio.auth_token'),
            'twilio_default_from_set' => (bool) config('titantalk.twilio.default_from'),
        ];

        return view('marketing-bot::voice.health.index', compact('checks'));
    }

    public function ping(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'module' => 'TitanHello',
            'time' => now()->toIso8601String(),
        ]);
    }
}
