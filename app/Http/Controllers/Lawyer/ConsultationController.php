<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Notification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        Notification::create([
            'user_id'  => $consultation->citizen_id,
            'type'     => 'consultation_update',
            'title'    => 'Consultation ' . ucfirst($request->status),
            'message'  => 'Your consultation on ' . $consultation->booked_date->format('d M Y') . ' has been ' . $request->status . '.',
            'link_url' => route('citizen.consultations.index'),
        ]);

        // Automatically add to Google Calendar when lawyer confirms
        if ($request->status === 'confirmed') {
            $this->addConsultationToCalendar($consultation);
        }

        return redirect()->back()->with('success', 'Consultation updated.');
    }

    // Add the confirmed consultation as an event on the lawyer's Google Calendar
    private function addConsultationToCalendar(Consultation $consultation): void
    {
        $lawyer = auth()->user();

        // Skip silently if the lawyer has not connected Google Calendar
        if (!$lawyer->google_access_token) {
            return;
        }

        $event = [
            'summary'     => 'Ain Sheba Consultation: ' . $consultation->citizen->name,
            'description' => 'Legal consultation booked via Ain Sheba. Citizen notes: ' . ($consultation->citizen_notes ?? ''),
            'start'       => [
                'dateTime' => $consultation->booked_date->format('Y-m-d') . 'T' . $this->parseTimeSlot($consultation->time_slot, 'start') . ':00',
                'timeZone' => 'Asia/Dhaka',
            ],
            'end'         => [
                'dateTime' => $consultation->booked_date->format('Y-m-d') . 'T' . $this->parseTimeSlot($consultation->time_slot, 'end') . ':00',
                'timeZone' => 'Asia/Dhaka',
            ],
            'reminders'   => ['useDefault' => true],
        ];

        try {
            $client = new Client();
            $client->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $lawyer->google_access_token,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $event,
            ]);
        } catch (\Throwable $e) {
            // Log the failure but do NOT interrupt the consultation confirmation flow
            Log::warning('Google Calendar event creation failed: ' . $e->getMessage());
        }
    }

    // Parse "10:00 - 11:00" → "10:00" (start) or "11:00" (end)
    private function parseTimeSlot(string $slot, string $part): string
    {
        $parts = explode(' - ', $slot);
        $time  = $part === 'start' ? ($parts[0] ?? '09:00') : ($parts[1] ?? '10:00');

        // Ensure HH:MM format (pad single-digit hours: "9:00" → "09:00")
        [$hour, $minute] = explode(':', $time);

        return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . $minute;
    }
}
