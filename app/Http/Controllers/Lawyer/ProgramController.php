<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\LegalArea;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::where('lawyer_id', auth()->id())
            ->with('legalArea')
            ->withCount('registrations');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $programs = $query->latest()->paginate(10);

        return view('lawyer.programs.index', compact('programs'));
    }

    public function create()
    {
        $legalAreas = LegalArea::orderBy('name')->get();

        return view('lawyer.programs.create', compact('legalAreas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'legal_area_id' => 'required|exists:legal_areas,id',
            'description'   => 'required|string',
            'level'         => 'required|in:basic,intermediate,advanced',
            'language'      => 'required|string|max:100',
            'status'        => 'required|in:draft,published,archived',
            'thumbnail'     => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->title);
        while (Program::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->title) . '-' . Str::random(4);
        }

        $path = null;
        if ($request->hasFile('thumbnail')) {
            $path = 'uploads/programs/program_' . time() . '_' . Str::random(6) . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('uploads/programs'), basename($path));
        }

        $program = Program::create([
            'lawyer_id'     => auth()->id(),
            'legal_area_id' => $request->legal_area_id,
            'title'         => $request->title,
            'slug'          => $slug,
            'description'   => $request->description,
            'thumbnail'     => $path,
            'level'         => $request->level,
            'language'      => $request->language,
            'status'        => $request->status,
        ]);

        return redirect()->route('lawyer.programs.show', $program->id)
            ->with('success', 'Program created successfully.');
    }

    public function show(int $id)
    {
        $program = Program::where('id', $id)
            ->where('lawyer_id', auth()->id())
            ->with([
                'modules'   => fn($q) => $q->orderBy('order_index'),
                'legalArea',
            ])
            ->withCount('registrations')
            ->withAvg('feedback', 'rating')
            ->firstOrFail();

        return view('lawyer.programs.show', compact('program'));
    }

    public function edit(int $id)
    {
        $program    = Program::where('id', $id)->where('lawyer_id', auth()->id())->firstOrFail();
        $legalAreas = LegalArea::orderBy('name')->get();

        return view('lawyer.programs.edit', compact('program', 'legalAreas'));
    }

    public function update(Request $request, int $id)
    {
        $program = Program::where('id', $id)->where('lawyer_id', auth()->id())->firstOrFail();

        $request->validate([
            'title'         => 'required|string|max:255',
            'legal_area_id' => 'required|exists:legal_areas,id',
            'description'   => 'required|string',
            'level'         => 'required|in:basic,intermediate,advanced',
            'language'      => 'required|string|max:100',
            'status'        => 'required|in:draft,published,archived',
            'thumbnail'     => 'nullable|image|max:2048',
        ]);

        $slug = $program->slug;
        if ($request->title !== $program->title) {
            $slug = Str::slug($request->title);
            while (Program::where('slug', $slug)->where('id', '!=', $program->id)->exists()) {
                $slug = Str::slug($request->title) . '-' . Str::random(4);
            }
        }

        $thumbnail = $program->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($program->thumbnail && file_exists(public_path($program->thumbnail))) {
                unlink(public_path($program->thumbnail));
            }
            $thumbnail = 'uploads/programs/program_' . time() . '_' . Str::random(6) . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('uploads/programs'), basename($thumbnail));
        }

        $program->update([
            'legal_area_id' => $request->legal_area_id,
            'title'         => $request->title,
            'slug'          => $slug,
            'description'   => $request->description,
            'thumbnail'     => $thumbnail,
            'level'         => $request->level,
            'language'      => $request->language,
            'status'        => $request->status,
        ]);

        return redirect()->route('lawyer.programs.show', $program->id)
            ->with('success', 'Program updated successfully.');
    }

    public function destroy(int $id)
    {
        $program = Program::where('id', $id)->where('lawyer_id', auth()->id())->firstOrFail();

        if ($program->thumbnail && file_exists(public_path($program->thumbnail))) {
            unlink(public_path($program->thumbnail));
        }

        $program->delete();

        return redirect()->route('lawyer.programs.index')
            ->with('success', 'Program deleted successfully.');
    }
}
