<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Voice;
use App\Mail\TrialWelcomeMail;
use App\Mail\TrialSignupAlertMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        $voices = Voice::all(); // Fetch all voices from the DB
        return view('frontend.auth.register', compact('voices'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'age_range' => 'nullable|in:under_18,18_24,25_34,35_44,45_54,55_plus',
            'profession' => 'nullable|string|min:2|max:100',
            'interests' => 'nullable|string',
            'voice_id' => 'required|exists:voices,id',
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
            'voice_id' => $request->voice_id, // ← include voice
            'password' => bcrypt($request->password),
            'plan_type' => $request->plan_type,
            'trial_ends_at' => $request->plan_type === 'trial' ? now()->addDays(7) : null,
        ]);

        if ($request->plan_type === 'trial') {
            Mail::to($user->email)->send(new TrialWelcomeMail($user));
            Mail::to('alerts@vibeliftdaily.com')->send(new TrialSignupAlertMail($user));
        }

        // event(new Registered($user)); // For email verification

        Auth::login($user);

        return $request->plan_type === 'subscribe'
            ? redirect()->route('subscription.page')
            : redirect()->route('dashboard')->with('success', 'Welcome! Your 7-day trial has started.');
    }



}