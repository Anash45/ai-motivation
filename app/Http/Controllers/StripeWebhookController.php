<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Log;
use Stripe\Webhook;
use Stripe\Subscription as StripeSubscription;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Stripe Webhook Started');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Error: Invalid Payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Error: Invalid Signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        Log::info('Stripe Webhook Received: ' . $event->type);

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                $subscriptionId = $invoice->subscription;

                if ($subscriptionId) {
                    $stripeSubscription = StripeSubscription::retrieve($subscriptionId);
                    $periodEnd = $stripeSubscription->current_period_end;

                    $user = User::where('stripe_subscription_id', $subscriptionId)->first();
                    if ($user) {
                        $user->plan_type = 'subscribe';
                        $user->is_subscribed = true;
                        $user->subscription_ends_at = Carbon::createFromTimestamp($periodEnd);
                        $user->save();

                        Log::info("✅ Subscription renewed for User ID {$user->id}");
                    }
                }
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $subscriptionId = $invoice->subscription;

                if ($subscriptionId) {
                    $user = User::where('stripe_subscription_id', $subscriptionId)->first();
                    if ($user) {
                        $user->plan_type = null;
                        $user->is_subscribed = false;
                        $user->save();

                        Log::warning("⚠ Subscription payment failed for User ID {$user->id}");
                    }
                }
                break;
        }

        return response()->json(['status' => 'success']);
    }

}