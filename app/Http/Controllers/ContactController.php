<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:100',
            'message' => 'nullable|string|max:1000',
        ]);

        Mail::to(env('APP_ADMIN_EMAIL'))->send(
            new ContactMessage($validated['name'], $validated['email'], $validated['message'])
        );

        return response()->json(['message' => 'Thank you! Your message has been sent.']);
    }
}
