<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('citizen.dashboard');
    }
}
