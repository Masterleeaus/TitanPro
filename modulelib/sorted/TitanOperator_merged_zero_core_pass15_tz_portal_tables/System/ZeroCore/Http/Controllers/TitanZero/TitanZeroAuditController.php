<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;

class TitanZeroAuditController extends Controller
{
    public function __construct(protected TitanZeroSystem $system) {}

    public function index()
    {
        return view('titan_operator::default.panel.user.titanzero.audit', [
            'audit' => $this->system->recentAudit(auth()->user()?->team_id),
        ]);
    }
}
