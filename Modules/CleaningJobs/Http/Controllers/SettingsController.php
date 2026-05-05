<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        // Pull module configuration using the titanwork key
        $cfg = config('titanwork');
        return view('titanwork::settings.index', ['cfg' => $cfg]);
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
        cache()->put('titanwork.settings.override', $data, now()->addYear());

        return redirect()->back()->with('status','TitanWork settings saved (cached). Consider publishing config for permanence.');
    }
}
