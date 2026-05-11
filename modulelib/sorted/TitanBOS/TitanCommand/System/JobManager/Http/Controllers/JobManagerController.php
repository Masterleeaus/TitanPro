<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;






class JobManagerController extends Controller
{
    public function index()
    {
        return view('jobmanager::index');
    }
}
