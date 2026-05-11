<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;






class SettingsController extends Controller
{
    public function index()
    {
        $cfg = config('jobmanager');
        return view('jobmanager::settings.index', ['cfg' => $cfg]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'api_auth' => ['nullable','boolean'],
            'webhook_url' => ['nullable','url'],
            'webhook_retries' => ['nullable','integer','min:0','max:10'],
            'webhook_backoff_seconds' => ['nullable','integer','min:0','max:120'],
        ]);

        // Persist to .env via simple env override advice (since config is publishable)
        // Here we just write to cache and suggest publishing config in README.
        // If you use a settings table, wire it here.
        cache()->put('jobmanager.settings.override', $data, now()->addYear());

        return redirect()->back()->with('status','Job Manager settings saved (cached). Consider publishing config for permanence.');
    }
}


    public function testWebhook()
    {
        $payload = ['event'=>'TestWebhook','timestamp'=>now()->toISOString()];
        $res = \App\Extensions\TitanCommand\System\JobManager\Services\WebhookSender::send($payload);
        return back()->with('status', $res['ok'] ? 'Webhook delivered ✅' : ('Webhook failed: '.$res['error']));
    }
