<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Voice;

class UserController extends Controller
{

    public function index()
    {
        $users = User::withCount('quotes')->get();

        return view('admin.users.index', compact('users'));
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Optional: Prevent deleting yourself
            if (auth()->id() == $user->id) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'You cannot delete your own account.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'An error occurred while deleting the user.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $voices = Voice::all();
        return view('admin.users.edit', compact('user', 'voices'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'voice_id' => 'required|exists:voices,id',
            'age_range' => 'nullable|in:under_18,18_24,25_34,35_44,45_54,55_plus',
            'profession' => 'nullable|string|max:255',
            'interests' => 'nullable|string|max:1000',
        ]);

        try {
            // Update basic details
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->voice_id = $validated['voice_id'];
            $user->age_range = $validated['age_range'] ?? null;
            $user->profession = $validated['profession'] ?? null;
            $user->interests = $validated['interests'] ?? null;

            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('admin.users.edit',$user->id)
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.edit',$user->id)
                ->with('error', 'An error occurred while updating your profile.');
        }
    }


}
