@component('mail::message')
# Good Morning {{ $user->name }},

We hope your day is off to an amazing start!  
Here's your **daily dose of motivation** — crafted just for you:

@component('mail::panel')
{{ $quote }}
@endcomponent

@component('mail::button', ['url' => $quoteLink])
View & Listen Online
@endcomponent

Prefer to listen offline? The audio version is attached to this email for your convenience.

Wishing you inspiration, clarity, and a successful day ahead!

Warm regards,  
**{{ config('app.name') }} Team**
@endcomponent