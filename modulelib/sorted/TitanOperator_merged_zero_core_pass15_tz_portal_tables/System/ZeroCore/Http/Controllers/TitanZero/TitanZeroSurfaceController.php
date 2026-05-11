<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;

class TitanZeroSurfaceController extends Controller
{
    public function __construct(protected TitanZeroSystem $system) {}

    public function boss()
    {
        return view('titan_operator::default.panel.user.titanzero.boss', $this->system->bossDashboard());
    }

    public function go()
    {
        return view('titan_operator::default.panel.user.titanzero.go', $this->system->goDashboard());
    }
}
