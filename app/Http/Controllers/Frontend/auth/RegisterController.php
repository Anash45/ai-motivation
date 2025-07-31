<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'age_range' => 'required|in:under_18,18_24,25_34,35_44,45_54,55_plus',
            'profession' => 'required|string|min:2|max:100',
            'interests' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'plan_type' => 'required|in:trial,subscribe',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'age_range' => $request->age_range,
            'profession' => $request->profession,
            'interests' => $request->interests,
            'password' => bcrypt($request->password),
            'plan_type' => $request->plan_type,
            'trial_ends_at' => $request->plan_type === 'trial' ? now()->addDays(7) : null,
            'subscribed' => $request->plan_type === 'subscribe',
        ]);

        event(new Registered($user)); // email verification

        return redirect()
            ->route('user.login') // or wherever you want
            ->with('success', 'Registration successful. Please verify your email.');
    }

}