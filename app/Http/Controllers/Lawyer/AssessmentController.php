<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    private function guardModule(int $moduleId): Module
    {
        $module = Module::with('program')->findOrFail($moduleId);
        if ($module->program->lawyer_id !== auth()->id()) {
            abort(403);
        }
        return $module;
    }

    public function create(int $moduleId)
    {
        $module = $this->guardModule($moduleId);
        return view('lawyer.assessments.create', compact('module'));
    }

    public function store(Request $request, int $moduleId)
    {
        $module = $this->guardModule($moduleId);

        $request->validate([
            'title'                             => 'required|string|max:255',
            'passing_score'                     => 'required|integer|between:0,100',
            'time_limit_minutes'                => 'nullable|integer|min:1',
            'questions'                         => 'required|array|min:1',
            'questions.*.question'              => 'required|string',
            'questions.*.type'                  => 'required|in:single,multiple,true_false',
            'questions.*.points'                => 'required|integer|min:1',
            'questions.*.options'               => 'required|array|min:2',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct'  => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($request, $module) {
            $assessment = Assessment::create([
                'module_id'          => $module->id,
                'title'              => $request->title,
                'passing_score'      => $request->passing_score,
                'time_limit_minutes' => $request->time_limit_minutes,
            ]);

            foreach ($request->questions as $qData) {
                $question = $assessment->questions()->create([
                    'question' => $qData['question'],
                    'type'     => $qData['type'],
                    'points'   => $qData['points'],
                ]);

                foreach ($qData['options'] as $opt) {
                    $question->options()->create([
                        'option_text' => $opt['option_text'],
                        'is_correct'  => isset($opt['is_correct']),
                    ]);
                }
            }
        });

        return redirect()->route('lawyer.modules.edit', [$module->program_id, $module->id])
            ->with('success', 'Assessment created.');
    }

    public function edit(int $moduleId, int $assessmentId)
    {
        $module     = $this->guardModule($moduleId);
        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);

        return view('lawyer.assessments.edit', compact('module', 'assessment'));
    }

    public function update(Request $request, int $moduleId, int $assessmentId)
    {
        $module     = $this->guardModule($moduleId);
        $assessment = Assessment::findOrFail($assessmentId);

        $request->validate([
            'title'                             => 'required|string|max:255',
            'passing_score'                     => 'required|integer|between:0,100',
            'time_limit_minutes'                => 'nullable|integer|min:1',
            'questions'                         => 'required|array|min:1',
            'questions.*.question'              => 'required|string',
            'questions.*.type'                  => 'required|in:single,multiple,true_false',
            'questions.*.points'                => 'required|integer|min:1',
            'questions.*.options'               => 'required|array|min:2',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct'  => 'sometimes|boolean',
        ]);

        DB::transaction(function () use ($request, $assessment) {
            $assessment->update([
                'title'              => $request->title,
                'passing_score'      => $request->passing_score,
                'time_limit_minutes' => $request->time_limit_minutes,
            ]);

            $assessment->questions()->delete();

            foreach ($request->questions as $qData) {
                $question = $assessment->questions()->create([
                    'question' => $qData['question'],
                    'type'     => $qData['type'],
                    'points'   => $qData['points'],
                ]);

                foreach ($qData['options'] as $opt) {
                    $question->options()->create([
                        'option_text' => $opt['option_text'],
                        'is_correct'  => isset($opt['is_correct']),
                    ]);
                }
            }
        });

        return redirect()->route('lawyer.modules.edit', [$module->program_id, $module->id])
            ->with('success', 'Assessment updated.');
    }

    public function destroy(int $moduleId, int $assessmentId)
    {
        $module = $this->guardModule($moduleId);
        Assessment::findOrFail($assessmentId)->delete();

        return redirect()->route('lawyer.modules.edit', [$module->program_id, $module->id])
            ->with('success', 'Assessment deleted.');
    }
}
