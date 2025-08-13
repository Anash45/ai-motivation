<?php

namespace App\Http\Controllers;
use App\Models\User;
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

    public function syncSubscriptionStatus(User $user)
    {
        Log::info("Starting subscription sync for user ID: {$user->id}");

        // Set your Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
        Log::info("Stripe API key set.");

        if (!$user->stripe_subscription_id) {
            Log::warning("No subscription ID found for user ID: {$user->id}");
            return response()->json(['message' => 'No subscription ID found'], 404);
        }

        try {
            Log::info("Retrieving subscription from Stripe for subscription ID: {$user->stripe_subscription_id}");

            // Retrieve subscription from Stripe
            $subscription = StripeSubscription::retrieve($user->stripe_subscription_id);
            Log::info("Stripe subscription retrieved successfully.", [
                'subscription_status' => $subscription->status,
                'current_period_end' => $subscription->current_period_end
            ]);

            // Get status
            $status = $subscription->status; // 'active', 'past_due', 'canceled', 'incomplete', etc.
            $endsAt = $subscription->current_period_end
                ? Carbon::createFromTimestamp($subscription->current_period_end)
                : null;

            Log::info("Parsed subscription status for user ID {$user->id}: {$status}");
            if ($endsAt) {
                Log::info("Subscription ends at: {$endsAt}");
            }

            // Update user in DB
            $user->update([
                'plan_type' => $status === 'active' ? 'subscribe' : null,
                'is_subscribed' => $status === 'active',
                'subscription_ends_at' => $endsAt,
            ]);

            Log::info("User subscription status updated in DB.", [
                'user_id' => $user->id,
                'new_status' => $status,
                'ends_at' => $endsAt
            ]);

            return response()->json([
                'message' => 'Subscription status synced successfully',
                'status' => $status,
                'ends_at' => $endsAt
            ]);
        } catch (\Exception $e) {
            Log::error("Error syncing subscription for user ID {$user->id}: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function syncAllSubscriptions()
    {
        Log::info("Starting bulk subscription sync for all users...");

        $users = User::whereNotNull('stripe_subscription_id')->get();
        Log::info("Found {$users->count()} users with subscription IDs.");

        $results = [];

        foreach ($users as $user) {
            Log::info("Syncing subscription for user ID: {$user->id}");
            $response = $this->syncSubscriptionStatus($user); // Reuse your existing method
            $results[] = [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => json_decode($response->getContent(), true)
            ];
        }

        Log::info("Bulk subscription sync completed.");

        return response()->json([
            'message' => 'All subscriptions synced successfully',
            'results' => $results
        ]);
    }


}
