<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrialOrSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow if user is in trial
        $inTrial = $user->trial_ends_at && now()->lt($user->trial_ends_at);

        // Allow if user is subscribed (Stripe - Laravel Cashier)
        $subscribed = method_exists($user, 'subscribed') && $user->subscribed('default');

        if ($inTrial || $subscribed) {
            return $next($request);
        }

        // Not allowed → redirect
        return redirect()->route('subscribe')->with('error', 'Please subscribe to continue.');
    }
}
