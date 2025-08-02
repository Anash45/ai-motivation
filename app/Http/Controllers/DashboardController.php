<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('frontend.dashboard.my-dashboard', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'age_range' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:500',
        ]);

        $user->name = $request->name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->age_range = $request->age_range;
        $user->profession = $request->profession;
        $user->interests = $request->interests;
        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profile updated successfully.');
    }

}
