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
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification(); // resend email
                Auth::logout();

                return redirect()->route('user.login')->withErrors([
                    'email' => 'Please verify your email. A new verification link has been sent to your email address.',
                ]);
            }


            $request->session()->regenerate();

            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/my-dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

}
