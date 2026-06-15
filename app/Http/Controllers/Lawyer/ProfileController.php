<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('lawyer.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'bio'              => 'nullable|string|max:1000',
            'consultation_fee' => 'nullable|numeric|min:0',
            'profile_picture'  => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }

            $path = 'uploads/profiles/profile_' . $user->id . '_' . time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profiles'), basename($path));
            $user->profile_picture = $path;
        }

        $user->name             = $request->name;
        $user->phone            = $request->phone;
        $user->bio              = $request->bio;
        $user->consultation_fee = $request->consultation_fee;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function showChangePassword()
    {
        return view('lawyer.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
