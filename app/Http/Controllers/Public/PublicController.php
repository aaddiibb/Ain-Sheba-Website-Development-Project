<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LawyerAvailability;
use App\Models\LegalArea;
use App\Models\Program;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function programs(Request $request)
    {
        $query = Program::where('status', 'published')
            ->with(['lawyer', 'legalArea'])
            ->withCount('registrations')
            ->withAvg('feedback', 'rating');

        if ($request->category) {
            $query->whereHas('legalArea', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->level) {
            $query->where('level', $request->level);
        }

        if ($request->search) {
            $keyword = $request->search;
            $query->where(fn($q) => $q->where('title', 'LIKE', "%$keyword%")->orWhere('description', 'LIKE', "%$keyword%"));
        }

        if ($request->sort === 'popular') {
            $query->orderByDesc('registrations_count');
        } elseif ($request->sort === 'level_asc') {
            $query->orderBy('level');
        } else {
            $query->latest();
        }

        $programs   = $query->paginate(9)->withQueryString();
        $legalAreas = LegalArea::all();

        return view('public.programs.index', compact('programs', 'legalAreas'));
    }

    public function programShow(string $slug)
    {
        $program = Program::where('slug', $slug)
            ->where('status', 'published')
            ->with([
                'lawyer.availability',
                'legalArea',
                'modules'  => fn($q) => $q->orderBy('order_index'),
                'feedback' => fn($q) => $q->with('citizen')->latest(),
            ])
            ->firstOrFail();

        $isRegistered = false;
        $registration = null;
        $nextModule = null;
        if (Auth::check() && Auth::user()->role === 'citizen') {
            $registration = Registration::where('citizen_id', Auth::id())
                ->where('program_id', $program->id)
                ->first();

            $isRegistered = (bool) $registration;
            $nextModule = $registration?->nextModule();
        }

        $registeredCount = $program->registrations()->count();

        return view('public.programs.show', compact('program', 'isRegistered', 'registeredCount', 'registration', 'nextModule'));
    }

    public function lawyerProfile(int $id)
    {
        $lawyer = User::where('id', $id)->where('role', 'lawyer')->where('is_active', true)->firstOrFail();

        $programs = Program::where('lawyer_id', $id)
            ->where('status', 'published')
            ->with('legalArea')
            ->withCount('registrations')
            ->withAvg('feedback', 'rating')
            ->get();

        $availability = LawyerAvailability::where('lawyer_id', $id)
            ->where('is_active', true)
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->get();

        $totalRegistrations = $programs->sum('registrations_count');
        $avgRating          = $programs->avg('feedback_avg_rating');
        $legalAreas         = LegalArea::whereIn('id', $programs->pluck('legal_area_id'))->get();

        return view('public.lawyers.show', compact('lawyer', 'programs', 'availability', 'totalRegistrations', 'avgRating', 'legalAreas'));
    }

    public function lawyers(Request $request)
    {
        $query = User::where('role', 'lawyer')->where('is_active', true);

        if ($request->search) {
            $query->where(fn($q) => $q
                ->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('bio', 'LIKE', "%{$request->search}%")
            );
        }

        $lawyers = $query
            ->withCount(['programs' => fn($q) => $q->where('status', 'published')])
            ->paginate(12)
            ->withQueryString();

        return view('public.lawyers.index', compact('lawyers'));
    }

    public function searchSuggest(Request $request)
    {
        if (strlen($request->get('q', '')) < 2) {
            return response()->json([]);
        }

        $programs = Program::where('status', 'published')
            ->where('title', 'LIKE', "%{$request->q}%")
            ->take(5)
            ->get(['title', 'slug']);

        return response()->json(['results' => $programs]);
    }
}
