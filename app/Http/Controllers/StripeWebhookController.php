<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $event = $request->all();

        if ($event['type'] === 'invoice.payment_succeeded') {
            $subscriptionId = $event['data']['object']['subscription'];

            $user = User::where('stripe_subscription_id', $subscriptionId)->first();

            if ($user) {
                $user->subscription_ends_at = Carbon::parse($user->subscription_ends_at)->addMonth();
                $user->is_subscribed = true;
                $user->save();
            }
        }

        if ($event['type'] === 'invoice.payment_failed') {
            $subscriptionId = $event['data']['object']['subscription'];

            $user = User::where('stripe_subscription_id', $subscriptionId)->first();

            if ($user) {
                $user->is_subscribed = false;
                $user->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}