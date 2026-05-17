<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Setting;

use App\Extensions\TitanLeads\System\Models\SmsChannel;
use App\Helpers\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsSettingController
{
    public function __invoke(Request $request)
    {
        if (Helper::appIsDemo()) {
            return back()->with([
                'status'  => 'error',
                'message' => trans('This feature is disabled in demo mode.'),
            ]);
        }

        $data = $request->validate([
            'account_sid' => 'nullable|string',
            'auth_token' => 'nullable|string',
            'from_number' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $channel = SmsChannel::query()->updateOrCreate([
            'user_id' => Auth::id(),
        ], array_merge($data, [
            'user_id' => Auth::id(),
            'provider' => 'twilio',
            'is_active' => (bool)($data['is_active'] ?? true),
        ]));

        return back()->with([
            'message' => __('SMS settings updated successfully.'),
            'type'    => 'success',
        ]);
    }
}
