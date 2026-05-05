<?php

namespace Modules\CleaningJobs\Http\Controllers;

use Illuminate\Routing\Controller;

class WorkOrdersController extends Controller
{
    public function index()
    {
        return view('cleaningjobs::index');
    }
}
