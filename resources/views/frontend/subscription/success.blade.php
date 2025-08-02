@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center py-5" style="min-height: 80vh;">
    <div class="text-center text-white py-5">
        <h2 class="mb-4">🎉 Subscription Successful!</h2>
        <p class="mb-4">Thank you for upgrading to Premium. Your motivational audios will now be delivered daily.</p>
        <a href="{{ route('user.dashboard') }}" class="btn btn-success px-4 py-2">Go to My Dashboard</a>
    </div>
</div>
@endsection