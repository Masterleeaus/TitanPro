<?php

namespace Modules\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;


namespace ModulesJobManagerHttpControllers;


namespace Modules\JobManager\Http\Controllers;

use Illuminate\Routing\Controller;

class JobManagerController extends Controller
{
    public function index()
    {
        return view('jobmanager::index');
    }
}
