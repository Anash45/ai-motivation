@component('mail::message')
# New Contact Message

**Name:** {{ $name }}

**Email:** {{ $email }}

**Message:**

{{ $messageContent }}

@component('mail::subcopy')
You received this message via the contact form on your website.
@endcomponent
@endcomponent