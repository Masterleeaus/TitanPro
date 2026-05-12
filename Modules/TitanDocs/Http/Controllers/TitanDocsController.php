<?php

namespace Modules\TitanDocs\Http\Controllers;

use Illuminate\Routing\Controller;

class TitanDocsController extends Controller
{
    public function index()
    {
        return view('titandocs::index');
    }

    public function create()
    {
        return view('titandocs::create');
    }

    public function history()
    {
        return view('titandocs::history');
    }
}
