<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['citizen', 'lawyer']);

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
            );
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isLawyer()) {
            $user->load(['programs' => fn($q) => $q->withCount('registrations')]);
        }

        if ($user->isCitizen()) {
            $user->load(['registrations' => fn($q) => $q->with('program')]);
        }

        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot deactivate admin accounts.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "User account {$status}.");
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
