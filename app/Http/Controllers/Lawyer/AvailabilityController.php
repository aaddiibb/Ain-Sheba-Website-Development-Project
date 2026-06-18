<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\LawyerAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availability = LawyerAvailability::where('lawyer_id', auth()->id())
            ->orderByRaw("FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->get();

        return view('lawyer.availability.index', compact('availability'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
        ]);

        LawyerAvailability::create([
            'lawyer_id'   => auth()->id(),
            'day_of_week' => $request->day_of_week,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'is_active'   => true,
        ]);

        return redirect()->back()->with('success', 'Availability slot added.');
    }

    public function destroy(int $id)
    {
        LawyerAvailability::where('lawyer_id', auth()->id())->findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Slot removed.');
    }
}
