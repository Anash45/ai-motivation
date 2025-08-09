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

    private function paypalClient()
    {
        $env = config('services.paypal.mode') === 'live'
            ? new \PayPalCheckoutSdk\Core\ProductionEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
            : new SandboxEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            );

        return new PayPalHttpClient($env);
    }
    
    private function getPayPalAccessToken()
    {
        $response = Http::asForm()->withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )->post(
                config('services.paypal.mode') === 'live'
                ? 'https://api-m.paypal.com/v1/oauth2/token'
                : 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
                [
                    'grant_type' => 'client_credentials',
                ]
            );

        if (!$response->successful()) {
            throw new \Exception('Unable to get PayPal access token.');
        }

        return $response->json()['access_token'];
    }

    public function startPaypalSubscription()
    {
        $accessToken = $this->getPayPalAccessToken();

        $response = Http::withToken($accessToken)->post(config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com/v1/billing/subscriptions'
            : 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions', [
            'plan_id' => 'YOUR_PAYPAL_PLAN_ID',
            'application_context' => [
                'brand_name' => 'Your Brand Name',
                'locale' => 'en-US',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'return_url' => route('subscription.paypal.approve'),
                'cancel_url' => route('subscription.paypal.cancel'),
            ]
        ]);

        if (!$response->successful()) {
            \Log::error('PayPal subscription creation failed:', $response->json());
            return redirect()->route('subscription.page')->with('error', 'Failed to create PayPal subscription.');
        }

        $links = collect($response->json()['links']);
        $approveLink = $links->firstWhere('rel', 'approve')['href'] ?? null;

        if (!$approveLink) {
            return redirect()->route('subscription.page')->with('error', 'Approval link not found.');
        }

        return redirect()->away($approveLink);
    }


    public function approvePaypalSubscription(Request $request)
    {
        $subscriptionId = $request->get('subscription_id');
        $accessToken = $this->getPayPalAccessToken();
        $user = Auth::user();

        $response = Http::withToken($accessToken)->get(config('services.paypal.mode') === 'live'
            ? "https://api-m.paypal.com/v1/billing/subscriptions/{$subscriptionId}"
            : "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/{$subscriptionId}");

        if (!$response->successful()) {
            \Log::error('PayPal get subscription failed:', $response->json());
            return redirect()->route('subscription.page')->with('error', 'Unable to retrieve subscription.');
        }

        $data = $response->json();

        if ($data['status'] === 'ACTIVE') {
            $user->update([
                'paypal_subscription_id' => $subscriptionId,
                'paypal_plan_id' => $data['plan_id'] ?? null,
                'paypal_status' => $data['status'],
                'is_subscribed' => true,
                'plan_type' => 'paypal',
                'subscription_ends_at' => now()->addMonth(), // adjust if using billing_cycle_anchor
            ]);

            return view('frontend.subscription.success');
        }

        return redirect()->route('subscription.page')->with('error', 'Subscription is not active.');
    }


    public function cancelPaypalSubscription()
    {
        return redirect()->route('subscription.page')->with('error', 'You cancelled the PayPal subscription.');
    }
}
