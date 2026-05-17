<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Routing\Controller;

class TrInOutPermitController extends Controller
{
    public function index()
    {
        return view('trinoutpermit::index');
    }
}
