<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice\Calls;

use Illuminate\Routing\Controller;
use App\Extensions\MarketingBot\System\Http\Requests\Voice\OutboundCallRequest;
use App\Extensions\MarketingBot\System\Services\Voice\Calls\OutboundCallService;

class DialerController extends Controller
{
    public function index()
    {
        return view('marketing-bot::voice.calls.dialer');
    }

    public function call(OutboundCallRequest $request, OutboundCallService $outbound)
    {
        $call = $outbound->placeCall(
            (string) $request->input('to_number'),
            (string) ($request->input('from_number') ?? '')
        );

        return redirect()->route('dashboard.user.marketing-bot.voice.calls.show', $call->id)
            ->with('success', 'Outbound call queued.');
    }
}
