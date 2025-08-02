@extends('layouts.app')

@section('content')
    <div class="py-5">
        <div class="container mt-5">
            <div class="card bg-dark text-white shadow-lg p-4">
                <h2 class="mb-3">My Dashboard</h2>
                {{-- Alerts --}}
                @foreach (['success' => 'success', 'error' => 'danger'] as $type => $class)
                    @if(session($type))
                        <div class="alert alert-{{ $class }}">{{ session($type) }}</div>
                    @endif
                @endforeach

                @php
                    use Carbon\Carbon;

                    $trialValid = $user->plan_type === 'trial' && $user->trial_ends_at && Carbon::parse($user->trial_ends_at)->isFuture();

                    $subscriptionEndsAt = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
                    $subscriptionEndsInFuture = $subscriptionEndsAt && $subscriptionEndsAt->isFuture();
                    $subscriptionEndsInPast = $subscriptionEndsAt && $subscriptionEndsAt->isPast();

                    $subscriptionEnds = $subscriptionEndsAt ? $subscriptionEndsAt->toFormattedDateString() : null;
                    $trialEnds = $user->trial_ends_at ? Carbon::parse($user->trial_ends_at)->toFormattedDateString() : null;
                @endphp

                @if ($user->is_subscribed && $subscriptionEndsInFuture)
                    {{-- 1. Active Subscription --}}
                    <div class="alert alert-info p-2">
                        You're subscribed. If you cancel, you'll still receive motivational quotes until
                        <strong>{{ $subscriptionEnds }}</strong>.
                    </div>

                    <form method="POST" action="{{ route('subscription.cancel-request') }}" id="cancel-subscription-form">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirmCancel()">
                            Cancel Subscription
                        </button>
                    </form>

                    <script>
                        function confirmCancel() {
                            return confirm("Are you sure you want to cancel your subscription?\nYou’ll still receive quotes until {{ $subscriptionEnds }}.");
                        }
                    </script>

                @elseif (!$user->is_subscribed && $subscriptionEndsInFuture)
                    {{-- 2. Cancelled Subscription but Still Valid --}}
                    <div class="alert alert-warning p-2">
                        You've cancelled your subscription, but it remains active until
                        <strong>{{ $subscriptionEnds }}</strong>.
                    </div>

                @elseif (!$user->is_subscribed && $trialValid)
                    {{-- 3. Active Trial --}}
                    <div class="alert alert-info p-2">
                        You're currently on a <strong>free trial</strong>. It will end on
                        <strong>{{ $trialEnds }}</strong>.
                        <a href="{{ route('subscription.page') }}" class="fw-bold">Subscribe now</a> to keep receiving
                        motivational quotes.
                    </div>

                @elseif ($user->plan_type === 'trial' && !$trialValid && !$user->is_subscribed && $user->trial_ends_at)
                    {{-- 4. Trial Expired --}}
                    <div class="alert alert-warning p-2">
                        Your <strong>free trial</strong> has ended.
                        <a href="{{ route('subscription.checkout') }}" class="fw-bold">Subscribe now</a> to keep receiving
                        quotes.
                    </div>

                @elseif (!$user->is_subscribed && !$trialValid && !$user->subscription_ends_at && $user->plan_type !== 'trial')
                    {{-- 5. Not Subscribed, No Trial --}}
                    <div class="alert alert-danger p-2">
                        You're currently not subscribed.
                        <a href="{{ route('subscription.checkout') }}" class="fw-bold">Subscribe now</a> to receive your daily
                        motivational quotes.
                    </div>

                @elseif ($user->is_subscribed && $subscriptionEndsInPast)
                    {{-- 6. Subscription Expired --}}
                    <div class="alert alert-warning p-2">
                        Your subscription expired on <strong>{{ $subscriptionEnds }}</strong>.
                        <a href="{{ route('subscription.checkout') }}" class="fw-bold">Renew now</a> to resume receiving daily
                        motivational quotes.
                    </div>
                @endif


                {{-- Profile Form --}}
                <form method="POST" novalidate class="needs-validation mt-4" action="{{ route('dashboard.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="form-control bg-secondary text-white" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password <small class="text-muted">(Leave blank to keep
                                current)</small></label>
                        <input name="password" type="password" class="form-control bg-secondary text-white">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="form-control bg-secondary text-white">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Age Range</label>
                        <input name="age_range" type="text" value="{{ old('age_range', $user->age_range) }}"
                            class="form-control bg-secondary text-white" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profession</label>
                        <input name="profession" type="text" value="{{ old('profession', $user->profession) }}"
                            class="form-control bg-secondary text-white" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Interests</label>
                        <textarea name="interests" class="form-control bg-secondary text-white" required
                            rows="3">{{ old('interests', $user->interests) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Update Profile</button>
                </form>

            </div>
        </div>
    </div>
@endsection