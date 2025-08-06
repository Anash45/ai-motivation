@component('mail::message')
# New Trial Signup

A new user has signed up for a trial:

- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Trial ends at:** {{ \Carbon\Carbon::parse($user->trial_ends_at)->format('M d, Y') }}

@endcomponent