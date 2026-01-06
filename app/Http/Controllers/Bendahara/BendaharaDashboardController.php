<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BendaharaDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('bendahara.dashboard');
    }
}

