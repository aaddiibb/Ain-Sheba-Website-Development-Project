<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Consultation;
use App\Models\Program;
use App\Models\Registration;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCitizens        = User::where('role', 'citizen')->count();
        $totalLawyers         = User::where('role', 'lawyer')->count();
        $totalPrograms        = Program::count();
        $publishedPrograms    = Program::where('status', 'published')->count();
        $totalRegistrations   = Registration::count();
        $totalCertificates    = Certificate::count();
        $totalConsultations   = Consultation::count();
        $pendingConsultations = Consultation::where('status', 'pending')->count();

        $recentUsers    = User::whereIn('role', ['citizen', 'lawyer'])->latest()->take(5)->get();
        $recentPrograms = Program::with('lawyer')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCitizens',
            'totalLawyers',
            'totalPrograms',
            'publishedPrograms',
            'totalRegistrations',
            'totalCertificates',
            'totalConsultations',
            'pendingConsultations',
            'recentUsers',
            'recentPrograms'
        ));
    }
}
