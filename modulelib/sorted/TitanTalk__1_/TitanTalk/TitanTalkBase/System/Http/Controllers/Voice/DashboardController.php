<?php

namespace App\Extensions\MarketingBot\System\Http\Controllers\Voice;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('marketing-bot::voice.dashboard.index');
    }
}
