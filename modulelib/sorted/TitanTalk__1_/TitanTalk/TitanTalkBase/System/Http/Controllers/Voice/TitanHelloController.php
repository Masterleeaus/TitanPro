<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice;

use Illuminate\Routing\Controller;

class TitanHelloController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard.user.marketing-bot.voice.calls.index');
    }
}
