<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with(['lawyer', 'legalArea'])->withCount('registrations');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $programs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.programs.index', compact('programs'));
    }

    public function show(int $id)
    {
        $program = Program::with(['lawyer', 'legalArea', 'modules'])
            ->withCount('registrations')
            ->withAvg('feedback', 'rating')
            ->findOrFail($id);

        return view('admin.programs.show', compact('program'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draft,published,archived',
        ]);

        $program->update(['status' => $request->status]);

        try {
            Notification::create([
                'user_id'  => $program->lawyer_id,
                'type'     => 'status_changed',
                'title'    => 'Program Status Updated',
                'message'  => 'Your program "' . $program->title . '" status was changed to ' . $request->status . ' by admin.',
                'link_url' => route('lawyer.programs.show', $program->id),
            ]);
        } catch (\Throwable $e) {
            // Notifications table may not exist yet — silently skip
        }

        return redirect()->back()->with('success', 'Program status updated.');
    }

    public function destroy(int $id)
    {
        Program::findOrFail($id)->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }
}
