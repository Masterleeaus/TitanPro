<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Setting;

use App\Extensions\TitanLeads\System\Models\EmailChannel;
use App\Helpers\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailSettingController
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
            'from_email' => 'nullable|string',
            'from_name' => 'nullable|string',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $channel = EmailChannel::query()->updateOrCreate([
            'user_id' => Auth::id(),
        ], array_merge($data, [
            'user_id' => Auth::id(),
            'provider' => 'smtp',
            'is_active' => (bool)($data['is_active'] ?? true),
        ]));

        return back()->with([
            'message' => __('Email settings updated successfully.'),
            'type'    => 'success',
        ]);
    }
}
