<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Notification;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $pending = Consultation::where('lawyer_id', auth()->id())
            ->where('status', 'pending')
            ->with('citizen')
            ->latest()
            ->get();

        $confirmed = Consultation::where('lawyer_id', auth()->id())
            ->where('status', 'confirmed')
            ->with('citizen')
            ->orderBy('booked_date')
            ->get();

        $past = Consultation::where('lawyer_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('citizen')
            ->latest()
            ->paginate(10);

        return view('lawyer.consultations.index', compact('pending', 'confirmed', 'past'));
    }

    public function updateStatus(Request $request, int $consultationId)
    {
        $consultation = Consultation::where('lawyer_id', auth()->id())->findOrFail($consultationId);

        $request->validate([
            'status'          => 'required|in:confirmed,completed,cancelled',
            'lawyer_response' => 'nullable|string|max:1000',
        ]);

        $consultation->update([
            'status'          => $request->status,
            'lawyer_response' => $request->lawyer_response,
        ]);

        try {
            Notification::create([
                'user_id'  => $consultation->citizen_id,
                'type'     => 'consultation_update',
                'title'    => 'Consultation ' . $request->status,
                'message'  => 'Your consultation on ' . $consultation->booked_date->format('d M Y') . ' has been ' . $request->status . '.',
                'link_url' => route('citizen.consultations.index'),
            ]);
        } catch (\Exception $e) {}

        return redirect()->back()->with('success', 'Consultation updated.');
    }
}
