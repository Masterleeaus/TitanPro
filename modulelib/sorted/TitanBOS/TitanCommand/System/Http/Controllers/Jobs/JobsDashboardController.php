<?php

declare(strict_types=1);

namespace App\Extensions\TitanCommand\System\Http\Controllers\Jobs;

use Illuminate\Routing\Controller;

class JobsDashboardController extends Controller
{
    public function index()
    {
        return view('titancommand::jobs.dashboard');
    }
}
