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
use Illuminate\Support\Facades\Log;
use Exception;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        $voices = Voice::all(); // Fetch all voices from the DB
        return view('frontend.auth.register', compact('voices'));
    }

    public function register(Request $request)
    {
        Log::error(message: "Registration Starts.");
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

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'age_range' => $request->age_range,
                'profession' => $request->profession,
                'interests' => $request->interests,
                'voice_id' => $request->voice_id,
                'password' => bcrypt($request->password),
                'plan_type' => $request->plan_type,
                'trial_ends_at' => $request->plan_type === 'trial' ? now()->addDays(7) : null,
            ]);

            if ($request->plan_type === 'trial') {
                Log::error(message: "Trial set.");
                try {
                    // Send welcome email to user
                    Mail::to($user->email)->send(new TrialWelcomeMail($user));
                    Log::info('Trial welcome email sent successfully', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'type' => 'trial_welcome'
                    ]);

                    // Send alert email to admin
                    Mail::to('alerts@vibeliftdaily.com')->send(new TrialSignupAlertMail($user));
                    Log::info('Trial signup alert email sent successfully', [
                        'user_id' => $user->id,
                        'type' => 'trial_alert'
                    ]);

                } catch (Exception $emailException) {
                    Log::info('Failed to send trial emails', [
                        'user_id' => $user->id,
                        'error' => $emailException->getMessage(),
                        'trace' => $emailException->getTraceAsString()
                    ]);

                    // Continue with registration even if email fails
                }
            } else {
                Log::error("Trial not set.");
            }

            Auth::login($user);

            return $request->plan_type === 'subscribe'
                ? redirect()->route('subscription.page')
                : redirect()->route('dashboard')->with('success', 'Welcome! Your 7-day trial has started.');

        } catch (Exception $e) {
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()
                ->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }



}