<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Feedback;
use App\Models\Program;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        $lawyer = auth()->user();

        $programs = Program::where('lawyer_id', $lawyer->id)
            ->withCount('registrations')
            ->latest()
            ->take(5)
            ->get();

        $totalPrograms       = Program::where('lawyer_id', $lawyer->id)->count();
        $publishedPrograms   = Program::where('lawyer_id', $lawyer->id)->where('status', 'published')->count();
        $draftPrograms       = Program::where('lawyer_id', $lawyer->id)->where('status', 'draft')->count();
        $totalRegistrations  = Registration::whereHas('program', fn($q) => $q->where('lawyer_id', $lawyer->id))->count();
        $totalFeedback       = Feedback::whereHas('program', fn($q) => $q->where('lawyer_id', $lawyer->id))->count();
        $pendingConsultations = Consultation::where('lawyer_id', $lawyer->id)->where('status', 'pending')->count();

        return view('lawyer.dashboard', compact(
            'lawyer',
            'programs',
            'totalPrograms',
            'publishedPrograms',
            'draftPrograms',
            'totalRegistrations',
            'totalFeedback',
            'pendingConsultations'
        ));
    }
}
