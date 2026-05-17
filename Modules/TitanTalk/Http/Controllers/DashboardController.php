<?php

namespace Modules\TitanTalk\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('titantalk::dashboard.index');
    }
}
