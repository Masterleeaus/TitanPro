<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\TitanTalk;

use App\Extensions\MarketingBot\System\Support\TitanTalkRealtimeConfig;
use App\Helpers\Classes\Helper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class RealtimeSettingsController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return to_route('dashboard.user.index')->with([
                'status' => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        return view('titantalk::operator.realtime-settings', [
            'config' => [
                'enabled' => TitanTalkRealtimeConfig::enabled(),
                'driver' => TitanTalkRealtimeConfig::driver(),
                'ably_public_key' => TitanTalkRealtimeConfig::ablyPublicKey(),
                'ably_private_key' => TitanTalkRealtimeConfig::ablyPrivateKey(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'titantalk_realtime_enabled' => 'nullable|boolean',
            'titantalk_realtime_driver' => 'required|string',
            'titantalk_ably_public_key' => 'nullable|string',
            'titantalk_ably_private_key' => 'nullable|string',
        ]);

        setting([
            'titantalk_realtime_enabled' => $request->boolean('titantalk_realtime_enabled'),
            'titantalk_realtime_driver' => $data['titantalk_realtime_driver'],
            'titantalk_ably_public_key' => $data['titantalk_ably_public_key'] ?? '',
            'titantalk_ably_private_key' => $data['titantalk_ably_private_key'] ?? '',
        ])->save();

        return back()->with([
            'type' => 'success',
            'message' => trans('TitanTalk realtime settings updated successfully.'),
        ]);
    }
}
