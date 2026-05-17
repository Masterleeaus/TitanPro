<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Routing\Controller;

class TrAccessCardController extends Controller
{
    public function index()
    {
        return view('traccesscard::index');
    }
}
