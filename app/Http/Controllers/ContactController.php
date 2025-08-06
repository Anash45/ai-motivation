<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Http;


class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:100',
            'message' => 'nullable|string|max:1000',
            'recaptcha_token' => 'required|string',
        ]);

        // Verify reCAPTCHA token
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $validated['recaptcha_token'],
        ]);

        $recaptchaResult = $recaptchaResponse->json();

        if (!($recaptchaResult['success'] ?? false) || ($recaptchaResult['score'] ?? 0) < 0.5) {
            return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
        }

        // Send mail if recaptcha passed
        Mail::to(env('APP_ADMIN_EMAIL'))->send(
            new ContactMessage($validated['name'], $validated['email'], $validated['message'])
        );

        return response()->json(['message' => 'Thank you! Your message has been sent.']);
    }

}
