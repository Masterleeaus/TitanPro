<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime;

use App\Http\Controllers\Controller;

class TitanRuntimeSurfaceController extends Controller
{
    public function dispatch()
    {
        return view('titan_operator::panel.user.titan-runtime.surfaces.dispatch');
    }

    public function qc()
    {
        return view('titan_operator::panel.user.titan-runtime.surfaces.qc');
    }

    public function boss()
    {
        return view('titan_operator::panel.user.titan-runtime.surfaces.boss');
    }

    public function go()
    {
        return view('titan_operator::panel.user.titan-runtime.surfaces.go');
    }
}
