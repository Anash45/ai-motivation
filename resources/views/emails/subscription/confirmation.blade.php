@component('mail::message')
# You're Subscribed!

Hi,

Thanks for subscribing to our upcoming launch. We’ll notify you with exclusive access and updates.

Stay tuned and vibe high!

@component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent