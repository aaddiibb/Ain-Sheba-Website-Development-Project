<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    private function guardRegistration(int $assessmentId): Assessment
    {
        $assessment = Assessment::with('module.program')->findOrFail($assessmentId);
        $registered = Registration::where('citizen_id', Auth::id())
            ->where('program_id', $assessment->module->program->id)
            ->exists();

        if (!$registered) {
            abort(403, 'You must be registered in this program to take this assessment.');
        }

        return $assessment;
    }

    public function show(int $assessmentId)
    {
        $assessment = $this->guardRegistration($assessmentId);
        $assessment->load('questions');

        $attempts = AssessmentAttempt::where('citizen_id', Auth::id())
            ->where('assessment_id', $assessmentId)
            ->latest('attempted_at')
            ->get();

        return view('citizen.assessments.show', compact('assessment', 'attempts'));
    }

    public function take(int $assessmentId)
    {
        $assessment = $this->guardRegistration($assessmentId);
        $assessment->load('questions.options');

        return view('citizen.assessments.take', compact('assessment'));
    }

    public function submit(Request $request, int $assessmentId)
    {
        $assessment = $this->guardRegistration($assessmentId);
        $assessment->load('questions.options');

        $totalPoints  = 0;
        $earnedPoints = 0;

        foreach ($assessment->questions as $question) {
            $totalPoints += $question->points;

            $submitted = collect((array) ($request->input("answers.{$question->id}", [])))
                ->map('intval')
                ->sort()
                ->values()
                ->toArray();

            $correct = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->sort()
                ->values()
                ->toArray();

            if ($submitted === $correct) {
                $earnedPoints += $question->points;
            }
        }

        $scorePercent = $totalPoints > 0 ? intval(($earnedPoints / $totalPoints) * 100) : 0;
        $passed       = $scorePercent >= $assessment->passing_score;

        $attempt = AssessmentAttempt::create([
            'citizen_id'    => Auth::id(),
            'assessment_id' => $assessmentId,
            'score'         => $scorePercent,
            'passed'        => $passed,
            'attempted_at'  => now(),
        ]);

        return redirect()->route('citizen.assessment.result', $attempt->id);
    }

    public function result(int $attemptId)
    {
        $attempt = AssessmentAttempt::with('assessment.module.program')
            ->where('citizen_id', Auth::id())
            ->findOrFail($attemptId);

        return view('citizen.assessments.result', compact('attempt'));
    }
}
