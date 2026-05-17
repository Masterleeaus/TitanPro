<?php

namespace Modules\ZeroPayModule\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class ExampleAdminController extends Controller
{
    public function dashboard()
    {
        return view('zeropay-module::admin.dashboard');
    }
}
