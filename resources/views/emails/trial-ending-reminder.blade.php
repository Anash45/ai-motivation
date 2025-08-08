@component('mail::message')
# Your Trial Ends Today, {{ $user->name }}!

Your free trial of Vibe Lift Daily **ends today** ({{ $trialEndsAt }}).

To continue receiving your daily motivational messages:

@component('mail::button', ['url' => route('subscription.page')])
Subscribe Now
@endcomponent

We'd love to keep you as part of our motivated community!


Stay inspired,  
**The Vibe Lift Daily Team**
@endcomponent