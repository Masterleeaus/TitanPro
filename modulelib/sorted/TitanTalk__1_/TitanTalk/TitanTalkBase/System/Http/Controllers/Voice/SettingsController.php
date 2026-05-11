<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $twilio = [
            'require_signature' => (bool) config('titantalk.twilio.require_signature'),
            'auth_token_set' => (bool) config('titantalk.twilio.auth_token'),
        ];

        return view('marketing-bot::voice.settings.index', compact('twilio'));
    }

    public function save(Request $request)
    {
        return redirect()->back()->with('status', 'Settings are env-driven in Pass 1 (no DB writes yet).');
    }
}
