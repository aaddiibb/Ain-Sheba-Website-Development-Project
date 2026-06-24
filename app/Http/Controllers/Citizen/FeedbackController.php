<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Registration;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, int $programId)
    {
        $registered = Registration::where('citizen_id', auth()->id())
            ->where('program_id', $programId)
            ->exists();

        if (!$registered) {
            return redirect()->back()->with('error', 'You must be registered to leave feedback.');
        }

        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Feedback::updateOrCreate(
            ['citizen_id' => auth()->id(), 'program_id' => $programId],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

    public function destroy(int $feedbackId)
    {
        $feedback = Feedback::where('citizen_id', auth()->id())->findOrFail($feedbackId);
        $feedback->delete();

        return redirect()->back()->with('success', 'Feedback removed.');
    }
}
