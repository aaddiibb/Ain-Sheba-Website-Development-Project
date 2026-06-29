<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalArea;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalAreaController extends Controller
{
    public function index()
    {
        $areas = LegalArea::withCount('programs')->get();

        return view('admin.legal-areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.legal-areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:legal_areas,name',
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        LegalArea::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'icon'        => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.legal-areas.index')->with('success', 'Legal area created.');
    }

    public function edit(int $id)
    {
        $area = LegalArea::findOrFail($id);

        return view('admin.legal-areas.edit', compact('area'));
    }

    public function update(Request $request, int $id)
    {
        $area = LegalArea::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100|unique:legal_areas,name,' . $id,
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $area->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'icon'        => $request->icon,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.legal-areas.index')->with('success', 'Legal area updated.');
    }

    public function destroy(int $id)
    {
        if (Program::where('legal_area_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Cannot delete: programs are using this legal area.');
        }

        LegalArea::findOrFail($id)->delete();

        return redirect()->route('admin.legal-areas.index')->with('success', 'Legal area deleted.');
    }
}
