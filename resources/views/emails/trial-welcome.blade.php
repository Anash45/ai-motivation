@component('mail::message')
# Welcome to Vibe Lift Daily, {{ $user->name }}!

You're currently on a **free trial**.  
It will end on **{{ $trialEndsAt }}**.  

@component('mail::button', ['url' => route('subscription.page')])
Subscribe Now
@endcomponent

Stay motivated,  
**Vibe Lift Team**
@endcomponent