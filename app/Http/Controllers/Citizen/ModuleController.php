<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function show(string $programSlug, int $moduleId)
    {
        $program = Program::where('slug', $programSlug)
            ->where('status', 'published')
            ->firstOrFail();

        $registration = Registration::where('citizen_id', Auth::id())
            ->where('program_id', $program->id)
            ->first();

        $modules = $program->modules()->orderBy('order_index')->get();
        $module = $modules->firstWhere('id', $moduleId) ?? abort(404);

        if (!$registration && !$module->is_free) {
            return redirect()->route('programs.show', $programSlug)
                ->with('error', 'Please register to access this module.');
        }

        $moduleProgress = null;
        $isCompleted = false;

        if ($registration) {
            $moduleProgress = ModuleProgress::firstOrCreate([
                'registration_id' => $registration->id,
                'module_id' => $module->id,
            ]);

            $isCompleted = (bool) $moduleProgress->completed_at;
        }

        $completedIds = $registration
            ? ModuleProgress::where('registration_id', $registration->id)
                ->whereNotNull('completed_at')
                ->pluck('module_id')
            : collect();

        $completedCount = $completedIds->count();
        $totalCount = $modules->count();
        $currentIndex = $modules->search(fn ($m) => $m->id === $module->id);

        $prevModule = $currentIndex > 0 ? $modules[$currentIndex - 1] : null;
        $nextModule = $currentIndex < $modules->count() - 1 ? $modules[$currentIndex + 1] : null;

        return view('citizen.module_viewer', compact(
            'program',
            'registration',
            'modules',
            'module',
            'moduleProgress',
            'isCompleted',
            'completedIds',
            'completedCount',
            'totalCount',
            'currentIndex',
            'prevModule',
            'nextModule'
        ));
    }

    public function markComplete(Request $request, int $moduleId)
    {
        $module = Module::with('program')->findOrFail($moduleId);

        $registration = Registration::where('citizen_id', Auth::id())
            ->where('program_id', $module->program_id)
            ->firstOrFail();

        $moduleProgress = ModuleProgress::where('registration_id', $registration->id)
            ->where('module_id', $moduleId)
            ->firstOrFail();

        if ($moduleProgress->completed_at) {
            return response()->json([
                'module_completed' => true,
                'program_completed' => !is_null($registration->completed_at),
            ]);
        }

        $moduleProgress->update(['completed_at' => now()]);

        $totalModules = Module::where('program_id', $module->program_id)->count();
        $completedCount = ModuleProgress::where('registration_id', $registration->id)
            ->whereNotNull('completed_at')
            ->count();
        $programCompleted = $completedCount === $totalModules;

        if ($programCompleted && is_null($registration->completed_at)) {
            $registration->update(['completed_at' => now()]);

            if (!Certificate::where('citizen_id', Auth::id())
                ->where('program_id', $module->program_id)
                ->exists()) {
                Certificate::create([
                    'citizen_id' => Auth::id(),
                    'program_id' => $module->program_id,
                    'certificate_code' => strtoupper(Str::random(12)),
                    'issued_at' => now(),
                ]);
            }

            try {
                if (class_exists('App\\Models\\Notification')) {
                    \App\Models\Notification::create([
                        'user_id' => $module->program->lawyer_id,
                        'type' => 'program_completed',
                        'title' => 'Program Completed',
                        'message' => Auth::user()->name . ' has completed your program: ' . $module->program->title,
                        'link_url' => route('lawyer.consultations.index'),
                    ]);
                }
            } catch (\Exception $e) {
            }
        }

        return response()->json([
            'module_completed' => true,
            'program_completed' => $programCompleted,
            'completed_count' => $completedCount,
            'total_count' => $totalModules,
        ]);
    }
}