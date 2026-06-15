<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Program;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    private function guardProgram(int $programId): Program
    {
        $program = Program::findOrFail($programId);
        if ($program->lawyer_id !== auth()->id()) {
            abort(403, 'This program does not belong to you.');
        }
        return $program;
    }

    public function create(int $programId)
    {
        $program = $this->guardProgram($programId);

        return view('lawyer.modules.create', compact('program'));
    }

    public function store(Request $request, int $programId)
    {
        $program = $this->guardProgram($programId);

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'nullable|string',
            'resource_url'     => 'nullable|url',
            'duration_minutes' => 'required|integer|min:0',
            'is_free'          => 'boolean',
        ]);

        $orderIndex = Module::where('program_id', $programId)->max('order_index') + 1;

        Module::create([
            'program_id'       => $programId,
            'title'            => $request->title,
            'content'          => $request->content,
            'resource_url'     => $request->resource_url,
            'order_index'      => $orderIndex,
            'duration_minutes' => $request->duration_minutes,
            'is_free'          => $request->boolean('is_free'),
        ]);

        return redirect()->route('lawyer.programs.show', $programId)
            ->with('success', 'Module added.');
    }

    public function edit(int $programId, int $moduleId)
    {
        $program = $this->guardProgram($programId);
        $module  = Module::where('program_id', $programId)->findOrFail($moduleId);

        return view('lawyer.modules.edit', compact('program', 'module'));
    }

    public function update(Request $request, int $programId, int $moduleId)
    {
        $this->guardProgram($programId);
        $module = Module::where('program_id', $programId)->findOrFail($moduleId);

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'nullable|string',
            'resource_url'     => 'nullable|url',
            'duration_minutes' => 'required|integer|min:0',
            'is_free'          => 'boolean',
        ]);

        $module->update([
            'title'            => $request->title,
            'content'          => $request->content,
            'resource_url'     => $request->resource_url,
            'duration_minutes' => $request->duration_minutes,
            'is_free'          => $request->boolean('is_free'),
        ]);

        return redirect()->route('lawyer.programs.show', $programId)
            ->with('success', 'Module updated.');
    }

    public function destroy(int $programId, int $moduleId)
    {
        $this->guardProgram($programId);
        $module = Module::where('program_id', $programId)->findOrFail($moduleId);
        $module->delete();

        return redirect()->route('lawyer.programs.show', $programId)
            ->with('success', 'Module deleted.');
    }

    public function reorder(Request $request, int $programId)
    {
        $this->guardProgram($programId);

        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
        ]);

        foreach ($request->ids as $index => $id) {
            Module::where('id', $id)
                ->where('program_id', $programId)
                ->update(['order_index' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
