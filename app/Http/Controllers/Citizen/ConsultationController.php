<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\LawyerAvailability;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $upcoming = Consultation::where('citizen_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('lawyer')
            ->orderBy('booked_date')
            ->get();

        $past = Consultation::where('citizen_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('lawyer')
            ->latest()
            ->paginate(8);

        return view('citizen.consultations.index', compact('upcoming', 'past'));
    }

    public function showBooking(int $lawyerId)
    {
        $lawyer = User::where('role', 'lawyer')->where('is_active', true)->findOrFail($lawyerId);

        $availability = LawyerAvailability::where('lawyer_id', $lawyerId)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        $bookedSlots = Consultation::where('lawyer_id', $lawyerId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['booked_date', 'time_slot']);

        return view('citizen.consultations.book', compact('lawyer', 'availability', 'bookedSlots'));
    }

    public function store(Request $request, int $lawyerId)
    {
        $request->validate([
            'booked_date'   => 'required|date|after:today',
            'time_slot'     => 'required|string',
            'citizen_notes' => 'nullable|string|max:500',
        ]);

        $slotTaken = Consultation::where('lawyer_id', $lawyerId)
            ->where('booked_date', $request->booked_date)
            ->where('time_slot', $request->time_slot)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($slotTaken) {
            return redirect()->back()->with('error', 'This time slot is already booked. Please choose another.');
        }

        $lawyer = User::findOrFail($lawyerId);

        $consultation = Consultation::create([
            'citizen_id'    => auth()->id(),
            'lawyer_id'     => $lawyerId,
            'booked_date'   => $request->booked_date,
            'time_slot'     => $request->time_slot,
            'fee'           => $lawyer->consultation_fee ?? 0,
            'status'        => 'pending',
            'citizen_notes' => $request->citizen_notes,
        ]);

        Notification::create([
            'user_id'  => $lawyerId,
            'type'     => 'new_consultation',
            'title'    => 'New Consultation Request',
            'message'  => auth()->user()->name . ' has requested a consultation on ' . $request->booked_date . ' at ' . $request->time_slot,
            'link_url' => route('lawyer.consultations.index'),
        ]);

        return redirect()->route('citizen.consultations.index')
            ->with('success', 'Consultation request sent! The lawyer will confirm shortly.');
    }

    public function cancel(int $consultationId)
    {
        $consultation = Consultation::where('citizen_id', auth()->id())->findOrFail($consultationId);

        if ($consultation->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $consultation->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Consultation cancelled.');
    }
}
