<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Subscription as StripeSubscription;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

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
                    'price' => env('STRIPE_PRICE_ID'),
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

            $user->plan_type = null;
            $user->save();

            return redirect()->route('user.dashboard')->with(
                'success',
                'Your subscription has been cancelled. You will continue receiving messages until ' .
                Carbon::parse($user->subscription_ends_at)->toFormattedDateString() . '.'
            );
        } catch (\Exception $e) {
            \Log::error('Stripe cancellation error: ' . $e->getMessage());

            return redirect()->route('user.dashboard')->with('error', 'There was an issue cancelling your subscription.');
        }
    }

    public function paypalApprove(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string'
        ]);

        $user = Auth::user();

        // 1️⃣ Optional: Validate subscription with PayPal API
        $paypalAccessToken = $this->getPaypalAccessToken();
        $response = Http::withToken($paypalAccessToken)
            ->get("https://api-m.sandbox.paypal.com/v1/billing/subscriptions/{$request->subscription_id}");

        if (!$response->successful()) {
            return response()->json(['success' => false, 'message' => 'Unable to verify subscription'], 422);
        }

        $subscriptionData = $response->json();

        // 2️⃣ Update user record
        $user->update([
            'paypal_subscription_id' => $subscriptionData['id'],
            'paypal_plan_id' => $subscriptionData['plan_id'],
            'paypal_status' => $subscriptionData['status'],
            'is_subscribed' => $subscriptionData['status'] === 'ACTIVE',
            'subscription_ends_at' => isset($subscriptionData['billing_info']['next_billing_time'])
                ? $subscriptionData['billing_info']['next_billing_time']
                : null
        ]);

        return response()->json(['success' => true]);
    }

    private function getPaypalAccessToken()
    {
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        $response = Http::asForm()->withBasicAuth($clientId, $secret)
            ->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return $response->json()['access_token'];
    }
}
