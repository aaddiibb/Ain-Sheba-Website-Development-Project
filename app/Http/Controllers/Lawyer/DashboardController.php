<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('lawyer.dashboard');
    }
}
