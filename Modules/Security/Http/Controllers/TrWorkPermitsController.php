<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Routing\Controller;

class TrWorkPermitsController extends Controller
{
    public function index()
    {
        return view('trworkpermits::index');
    }
}
