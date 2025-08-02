<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Subscription as StripeSubscription;
class SubscriptionController extends Controller
{
    /**
     * Show the subscription plan selection page.
     */

    public function show()
    {
        return view('frontend.subscription.show');
    }

    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [
                [
                    'price' => 'price_1RrTDyRqAOa4P3rrPlpEjPj6', // Your actual recurring price ID
                    'quantity' => 1,
                ]
            ],
            'customer_email' => auth()->user()->email,
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscription.cancel'),
        ]);

        return redirect()->away($session->url);
    }


    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('subscription.cancel')->with('error', 'No session ID provided.');
        }

        $session = Session::retrieve($sessionId);

        $subscriptionId = $session->subscription;

        if (!$subscriptionId) {
            return redirect()->route('subscription.cancel')->with('error', 'Subscription not found.');
        }

        $subscription = Subscription::retrieve($subscriptionId);

        // 🔍 Log full subscription object for debugging
        Log::info('Stripe Subscription Data:', (array) $subscription);

        $periodEnd = $subscription->items->data[0]->current_period_end ?? null;

        if (!$periodEnd) {
            return redirect()->route('subscription.cancel')->with('error', 'Could not retrieve subscription end date.');
        }

        $user = auth()->user();
        $user->plan_type = "subscribe";
        $user->is_subscribed = true;
        $user->stripe_subscription_id = $subscription->id;
        $user->subscription_ends_at = Carbon::createFromTimestamp($periodEnd);
        $user->save();

        return view('frontend.subscription.success');
    }

    public function cancelSubscription()
    {
        $user = Auth::user();

        if (!$user->is_subscribed || !$user->stripe_subscription_id) {
            return redirect()->route('user.dashboard')->with('error', 'You are not currently subscribed.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            StripeSubscription::update($user->stripe_subscription_id, [
                'cancel_at_period_end' => true,
            ]);

            $user->is_subscribed = false;
            $user->save();

            return redirect()->route('user.dashboard')->with(
                'success',
                'Your subscription has been cancelled. You will continue receiving quotes until ' .
                Carbon::parse($user->subscription_ends_at)->toFormattedDateString() . '.'
            );
        } catch (\Exception $e) {
            \Log::error('Stripe cancellation error: ' . $e->getMessage());

            return redirect()->route('user.dashboard')->with('error', 'There was an issue cancelling your subscription.');
        }
    }


}
