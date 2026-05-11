<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Engines\TitanZeroEngine;

class TitanZeroController extends Controller
{
    public function __construct(protected TitanZeroEngine $engine) {}

    public function index()
    {
        return view('titan_operator::default.panel.user.titanzero.index', $this->engine->indexPayload());
    }
}
