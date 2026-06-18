<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request, int $programId)
    {
        $program = Program::where('status', 'published')->findOrFail($programId);

        if (Registration::where('citizen_id', auth()->id())->where('program_id', $programId)->exists()) {
            return redirect()->back()->with('error', 'You are already registered in this program.');
        }

        Registration::create([
            'citizen_id'    => auth()->id(),
            'program_id'    => $programId,
            'registered_at' => now(),
        ]);

        return redirect()->route('programs.show', $program->slug)
            ->with('success', 'You are now registered! Start learning.');
    }
}
