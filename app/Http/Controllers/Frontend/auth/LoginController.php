<?php
namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification(); // Resend email
                return redirect()->route('verification.notice'); // Keep them logged in
            }

            // Redirect based on user role
            return $user->isAdmin()
                ? redirect()->intended('/dashboard')
                : redirect()->intended('/my-dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

}
