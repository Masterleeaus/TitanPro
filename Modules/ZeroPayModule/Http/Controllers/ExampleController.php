<?php

namespace Modules\ZeroPayModule\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Services\Contracts\ZeroPayModuleServiceContract;

class ExampleController extends Controller
{
    public function index(ZeroPayModuleServiceContract $service)
    {
        return view('zeropay-module::index', ['records' => $service->listForCompany(auth()->user()->company_id)]);
    }

    public function store()
    {/* validate and call CreateZeroPaySessionAction */
    }
}
