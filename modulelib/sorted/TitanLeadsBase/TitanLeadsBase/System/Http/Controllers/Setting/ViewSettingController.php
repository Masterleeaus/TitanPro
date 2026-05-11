<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Setting;

use App\Extensions\TitanLeads\System\Models\Telegram\TelegramBot;
use App\Extensions\TitanLeads\System\Models\Whatsapp\WhatsappChannel;
use App\Extensions\TitanLeads\System\Models\SmsChannel;
use App\Extensions\TitanLeads\System\Models\VoiceChannel;
use App\Extensions\TitanLeads\System\Models\EmailChannel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewSettingController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('titan-leads::settings.index', [
            'telegram' => TelegramBot::query()->where('user_id', Auth::id())->first(),
            'whatsapp' => WhatsappChannel::query()->where('user_id', Auth::id())->first(),
            'sms'      => SmsChannel::query()->where('user_id', Auth::id())->first(),
            'voice'    => VoiceChannel::query()->where('user_id', Auth::id())->first(),
            'email'    => EmailChannel::query()->where('user_id', Auth::id())->first(),
        ]);
    }
}
