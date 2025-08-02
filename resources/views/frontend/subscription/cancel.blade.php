@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center py-5" style="min-height: 80vh;">
    <div class="text-center text-white py-5">
        <h2 class="mb-4">😕 Subscription Canceled</h2>
        <p class="mb-4">It seems you canceled the payment process. No worries, you can always subscribe later!</p>
        <a href="{{ route('subscription.page') }}" class="btn btn-outline-light px-4 py-2">Try Again</a>
    </div>
</div>
@endsection