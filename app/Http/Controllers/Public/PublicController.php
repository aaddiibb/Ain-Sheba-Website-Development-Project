<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LegalArea;
use App\Models\Program;
use App\Models\User;

class PublicController extends Controller
{
    public function home()
    {
        $programs      = Program::where('status', 'published')->with(['lawyer', 'legalArea'])->latest()->take(6)->get();
        $legalAreas    = LegalArea::all();
        $totalPrograms = Program::where('status', 'published')->count();
        $totalCitizens = User::where('role', 'citizen')->count();
        $totalLawyers  = User::where('role', 'lawyer')->count();

        return view('public.home', compact('programs', 'legalAreas', 'totalPrograms', 'totalCitizens', 'totalLawyers'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }
}
