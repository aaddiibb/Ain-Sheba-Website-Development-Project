<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Consultation;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $citizen = auth()->user();

        $registrations = Registration::where('citizen_id', $citizen->id)
            ->with(['program.legalArea', 'program.lawyer'])
            ->get();

        $totalEnrolled      = $registrations->count();
        $inProgress         = $registrations->filter(fn($r) => is_null($r->completed_at))->count();
        $completed          = $registrations->filter(fn($r) => !is_null($r->completed_at))->count();
        $totalCertificates  = Certificate::where('citizen_id', $citizen->id)->count();

        $continuePrograms = $registrations->filter(fn($r) => is_null($r->completed_at))->take(3);

        $recommended = Program::where('status', 'published')
            ->whereNotIn('id', $registrations->pluck('program_id'))
            ->with(['lawyer', 'legalArea'])
            ->latest()
            ->take(3)
            ->get();

        $upcomingConsultations = Consultation::where('citizen_id', $citizen->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('lawyer')
            ->orderBy('booked_date')
            ->take(3)
            ->get();

        return view('citizen.dashboard', compact(
            'citizen',
            'registrations',
            'totalEnrolled',
            'inProgress',
            'completed',
            'totalCertificates',
            'continuePrograms',
            'recommended',
            'upcomingConsultations'
        ));
    }

    public function myPrograms(Request $request)
    {
        $query = Registration::where('citizen_id', auth()->id())
            ->with([
                'program.legalArea',
                'program.lawyer',
                'program.certificates' => fn($q) => $q->where('citizen_id', auth()->id()),
            ]);

        $status = $request->query('status');

        if ($status === 'in-progress') {
            $query->whereNull('completed_at');
        } elseif ($status === 'completed') {
            $query->whereNotNull('completed_at');
        }

        $registrations = $query->paginate(9);

        return view('citizen.programs', compact('registrations'));
    }

    public function profile()
    {
        return view('citizen.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'bio'             => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            $path = 'uploads/profiles/profile_' . $user->id . '_' . time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profiles'), basename($path));
            $user->profile_picture = $path;
        }

        $user->name  = $request->name;
        $user->phone = $request->phone;
        $user->bio   = $request->bio;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function showChangePassword()
    {
        return view('citizen.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
