<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\SubscriptionConfirmation;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please enter a valid email address.'
            ]);
        }

        $email = $request->email;

        // Check if already subscribed
        if (Subscriber::where('email', $email)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This email is already subscribed.'
            ]);
        }

        // Save subscriber
        $subscriber = Subscriber::create([
            'email' => $email,
        ]);

        // Send confirmation email (optional — remove if not implemented yet)
        try {
            Mail::to($email)->send(new SubscriptionConfirmation($email));
        } catch (\Exception $e) {
            // Log error but still return success
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Thanks for subscribing! You’ll be the first to know when we launch.'
        ]);
    }

}
